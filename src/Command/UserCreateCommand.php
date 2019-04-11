<?php

namespace CascadePublicMedia\PbsApiExplorer\Command;

use CascadePublicMedia\PbsApiExplorer\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\Exception\RuntimeCommandException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserCreateCommand
 *
 * @package CascadePublicMedia\PbsApiExplorer\Command
 */
class UserCreateCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:user:create';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $roles;

    /**
     * {@inheritdoc}
     */
    public function __construct(EntityManagerInterface $entityManager,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = Validation::createValidator();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Creates a new app user.')
            ->setHelp('This command helps you create a user who can log in to the app.')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email address')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new ConsoleStyle($input, $output);

        $arg1 = $input->getArgument('email');
        $question = new Question('User email address', $arg1);
        $question->setValidator([$this, 'validateEmail']);
        $this->email = $io->askQuestion($question);

        $question = new Question('User password', $arg1);
        $question->setValidator([$this, 'validatePassword']);
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $this->password = $io->askQuestion($question);

        $question = new ChoiceQuestion('Roles', ['ROLE_ADMIN']);
        $question->setMultiselect(true);
        $this->roles = $io->askQuestion($question);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $user = new User();
        $user->setEmail($this->email);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $this->password
        ));
        $user->setRoles($this->roles);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io = new ConsoleStyle($input, $output);
        $io->success('User created!');
    }

    /**
     * Validate email format and that a user with the email doesn't already
     * exist.
     *
     * @param $email
     *
     * @return string
     *
     * @throws RuntimeCommandException
     */
    public function validateEmail($email) {
        $violations = $this->validator->validate($email, [
            new NotBlank(),
            new Email()
        ]);
        if (count($violations) > 0) {
            throw new RuntimeCommandException($violations[0]->getMessage());
        }

        // Check for an existing user with this email address.
        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneByEmail($email);
        if (!is_null($user)) {
            throw new RuntimeCommandException('A user with that email address already exists.');
        }
        return $email;
    }

    /**
     * Validate simple three character minimum.
     *
     * @param $password
     *
     * @return string
     *
     * @throws RuntimeCommandException
     */
    public function validatePassword($password) {
        $violations = $this->validator->validate($password, [
            new NotBlank(),
            new Length(['min' => 3])
        ]);
        if (count($violations) > 0) {
            throw new RuntimeCommandException($violations[0]->getMessage());
        }
        return $password;
    }
}
