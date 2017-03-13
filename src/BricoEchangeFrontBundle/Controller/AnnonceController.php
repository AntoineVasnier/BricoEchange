<?php

namespace BricoEchangeFrontBundle\Controller;

use BricoEchangeFrontBundle\Entity\Annonce;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AnnonceController extends Controller {

    /**
     * @Template()
     */
    public function viewAction($id) {
        if (!$id) {
            throw $this->createNotFoundException('Aucunne annonce trouvé');
        }
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('BricoEchangeFrontBundle:Annonce');
        $annonce = $rep->find($id);
        return $this->render('BricoEchangeFrontBundle:Annonce:view.html.twig', array('annonce' => $annonce));
    }

    /**
     * @Template()
     */
    public function categAction($id) {
        if (!$id) {
            throw $this->createNotFoundException('Aucunne categorie trouvé');
        }
        $em = $this->getDoctrine()->getManager();
        $repAnnonce = $em->getRepository('BricoEchangeFrontBundle:Annonce');
        $repCateg = $em->getRepository('BricoEchangeFrontBundle:Categorie');
        $categorie = $repCateg->find($id);
        $annonces = $repAnnonce->getAnnoncesCateg($categorie);
        return $this->render('BricoEchangeFrontBundle:Annonce:categ.html.twig', array('annonces' => $annonces, 'categorie'=> $categorie));
    }
    
    
    /**
     * @Template()
     */
    public function ajoutAction() {
        $annonce = new Annonce();
        $form = $this->getFormulaireAjout($annonce);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /** @var UploadedFile $file */
            $file = $annonce->getPhoto();
            $fileName = $file->getClientOriginalName();
            $file->move($this->getParameter('images_annonce'), $fileName);

            $annonce->setDescription($form->get('description')->getData())
                    ->setResume($form->get('resume')->getData())
                    ->setPhoto($fileName)
                    ->setEmail($form->get('email')->getData())
                    ->setVille($form->get('ville')->getData())
                    ->setMarque($form->get('marque')->getData())
                    ->setDatePublication(new DateTime(date('Y-m-d')))
                    ->setReprise(false);
            $em->persist($annonce);
            $em->flush();

            return $this->redirect($this->generateUrl('index'));
        }
        return array('annonce' => $annonce, 'form' => $form->createView());
    }

    protected function getFormulaireAjout($annonce) {
        $builder = $this->createFormBuilder($annonce);
        //Add form fields
        $builder->add('type', 'entity', array(
                    'class' => 'BricoEchangeFrontBundle:Type',
                    'query_builder' => function(EntityRepository $rep) {
                        return $rep->createQueryBuilder('t');
                    },
                    'property' => 'libelle',
                    'label' => 'Type d\'annonce : '
                ))
                ->add('categorie', 'entity', array(
                    'class' => 'BricoEchangeFrontBundle:Categorie',
                    'query_builder' => function(EntityRepository $rep) {
                        return $rep->createQueryBuilder('c');
                    },
                    'property' => 'libelle',
                    'label' => 'Catégorie : '
                ))
                ->add('resume', 'text', array(
                    'constraints' => array(new NotBlank(), new Length(array(
                            'min' => 5,
                            'max' => 50,
                            'minMessage' => 'Le résumé de l\'annonce doit comporter au minimum {{ limit }} caractères',
                            'maxMessage' => 'Le résumé de l\'annonce doit comporter au maximum {{ limit }} caractères',
                                ))),
                    'label' => 'Résumé de l\'annonce : '
                ))
                ->add('marque', 'text', array(
                    'constraints' => array(new NotBlank(), new Length(array(
                            'min' => 5,
                            'max' => 100,
                            'minMessage' => 'La marque doit comporter au minimum {{ limit }} caractères',
                            'maxMessage' => 'La marque doit comporter au maximum {{ limit }} caractères',
                                ))),
                    'label' => 'Marque : '
                ))
                ->add('description', 'textarea', array(
                    'constraints' => array(new NotBlank(), new Length(array(
                            'min' => 20,
                            'max' => 255,
                            'minMessage' => 'L\'annonce doit comporter au minimum {{ limit }} caractères',
                            'maxMessage' => 'L\'annonce doit comporter au maximum {{ limit }} caractères',
                                ))),
                    'label' => 'Description de l\'annonce : '
                ))
                ->add('photo', 'file')
                ->add('email', 'text', array(
                    'constraints' => array(
                        new NotBlank(),
                        new Length(array(
                            'min' => 5,
                            'max' => 100,
                            'minMessage' => 'L\'email doit comporter au minimum {{ limit }} caractères',
                            'maxMessage' => 'L\'email doit comporter au maximum {{ limit }} caractères',
                                ))
                    ),
                    'label' => 'Email : '
                ))
                ->add('ville', 'text', array(
                    'constraints' => array(
                        new NotBlank(),
                        new Length(array(
                            'min' => 2,
                            'max' => 100,
                            'minMessage' => 'La ville doit comporter au minimum {{ limit }} caractères',
                            'maxMessage' => 'La ville doit comporter au maximum {{ limit }} caractères',
                                ))
                    ),
                    'label' => 'Ville : ',
                ))
                ->add('valider', 'submit');
        $form = $builder->getForm();
        $form->handleRequest($this->get('request'));

        return $form;
    }

}
