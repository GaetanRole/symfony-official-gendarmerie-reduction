<?php

/**
 * User FormType File
 *
 * PHP Version 7.2
 *
 * @category    User
 * @package     App\Form
 * @version     1.0
 * @author      Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */

namespace App\Form;

use App\Entity\User;
use App\Form\DataTransformer\ArrayToStringTransformer;
use App\Form\Type\ChangePasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * User FormType Class
 *
 * @category    User
 * @package     App\Form
 * @author      Gaëtan Rolé-Dubruille <gaetan.role@gmail.com>
 */
class UserType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $auth;

    /**
     * UserType constructor.
     *
     * @see To use isGranted() in buildForm
     * @param AuthorizationCheckerInterface $auth
     */
    public function __construct(AuthorizationCheckerInterface $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Building form
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'username',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'form.user.username.label',
                    'help' => 'form.user.username.help',
                    'attr' => [
                        'placeholder' => 'form.user.username.placeholder',
                        'minLength' => '2',
                        'maxLength' => '64',
                    ],
                ]
            )
            ->add(
                'identity',
                TextType::class,
                [
                    'required' => true,
                    'label' => 'form.user.identity.label',
                    'help' => 'form.user.identity.help',
                    'attr' => [
                        'placeholder' => 'form.user.identity.placeholder',
                        'minLength' => '2',
                        'maxLength' => '64',
                    ],
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'required' => false,
                    'empty_data' => null,
                    'label' => 'form.user.email.label',
                    'help' => 'form.user.email.help',
                    'attr' => [
                        'placeholder' => 'form.user.email.placeholder',
                        'maxLength' => '64',
                    ],
                ]
            )
            ->add(
                'phoneNumber',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'form.user.phone_number.label',
                    'help' => 'form.user.phone_number.help',
                    'attr' => [
                        'placeholder' => 'form.user.phone_number.placeholder',
                        'maxLength' => '32',
                    ],
                ]
            )
            ->add(
                'isActive',
                CheckboxType::class,
                [
                    'required' => false,
                    'label'    => 'form.user.is_active.label',
                    'help' => 'form.user.is_active.help'
                ]
            );
        if ($this->auth->isGranted('ROLE_SUPER_ADMIN')) {
            $builder
                ->add(
                    'roles',
                    ChoiceType::class,
                    [
                        'label'    => 'form.user.roles.label',
                        'choices' => [
                            'form.user.roles.choices.role_user' => 'ROLE_USER',
                            'form.user.roles.choices.role_admin' => 'ROLE_ADMIN'
                        ],
                        'expanded' => false,
                        'multiple' => false,
                        'empty_data' => 'ROLE_USER',
                        'help' => 'form.user.roles.help'
                    ]
                )
                ->get('roles')
                ->addModelTransformer(new ArrayToStringTransformer(), true);
        }
        if (empty($options['data']->getPassword())) {
            $builder->add(
                'plainPassword',
                ChangePasswordType::class,
                [
                    'required' => true,
                    'label' => false,
                    'inherit_data' => true,
                    'help' => 'form.user.plain_password.help',
                ]
            );
        }
    }

    /**
     * Set User class
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'translation_domain' => 'forms',
        ]);
    }
}
