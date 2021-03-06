<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class BookEditorType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options) : void{
        $builder->add('book_title',  TextType::class,    ['label'=> 'Имя',
                                                          'empty_data' => ''])
                ->add('issue_year',  IntegerType::class, ['label'=> 'Год',
                                                          'empty_data' => '',
                                                          'invalid_message' => 'Год должен быть целым положительным числом!'])
                ->add('author_name', TextType::class,    ['label'=> 'Автор',
                                                          'empty_data' => ''])
                ->add('submit',      SubmitType::class,  ['label'=> 'Сохранить'])
                ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver) : void{
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
