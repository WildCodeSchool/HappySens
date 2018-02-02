<?php
/**
 * Created by PhpStorm.
 * User: aurelie
 * Date: 12/01/18
 * Time: 15:50
 */

namespace AppBundle\Form;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditHappyCoachInProjectType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('happyCoach', EntityType::class, [
            'class' => User::class,
            'required' => false,
            'empty_data' => null,
            'multiple' => false,
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->setParameter('happyCoach', USER::ROLE_HAPPYCOACH)
                    ->where('u.status = :happyCoach')
                    ->andWhere('u.isActive = true');
            },
        ])
            ->add('teamProject', EntityType::class, [
                'class' => User::class,
                'required' => false,
                'empty_data' => null,
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->setParameter('happyCoach', USER::ROLE_HAPPYCOACH)
                        ->where('u.status = :happyCoach')
                        ->andWhere('u.isActive = true');
                },
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Project',
            'validation_groups' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_project';
    }

}