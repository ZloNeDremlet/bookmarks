<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     *
     * @throws \Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', null, ['description' => 'Текст комментария'])
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => 'AppBundle\Entity\Comment']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Comment';
    }
}
