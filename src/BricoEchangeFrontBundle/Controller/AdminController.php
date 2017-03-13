<?php
namespace BricoEchangeFrontBundle\Controller;

use BricoEchangeFrontBundle\Entity\Categorie;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminController extends Controller
{
    /**
    * @Template()
    */
    public function indexAction()
    {      
        return $this->render('BricoEchangeFrontBundle:Admin:index.html.twig');
    }
    
    public function categorieAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('BricoEchangeFrontBundle:Categorie');
        $categories = $rep->findAll();
        return $this->render('BricoEchangeFrontBundle:Admin:categorie.html.twig', array('categories' => $categories));
    }
    
    public function annonceAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('BricoEchangeFrontBundle:Annonce');
        $annonces = $rep->findAll();
        return $this->render('BricoEchangeFrontBundle:Admin:annonce.html.twig', array('annonces' => $annonces));
    }
    
    public function utilisateurAction()
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('BricoEchangeFrontBundle:Utilisateur');
        $utilisateurs = $rep->findAll();
        return $this->render('BricoEchangeFrontBundle:Admin:utilisateur.html.twig', array('utilisateurs' => $utilisateurs));
    }
    
    /**
    * @Template()
    */
    public function modifierUtilisateurAction($id)
    {  
        if (!$id){
            throw $this->createNotFoundException('Aucun utilisateur trouvée');
        }
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('BricoEchangeFrontBundle:Utilisateur');
        $utilisateur = $rep->find($id);
        $formModifUtilisateur = $this->createFormBuilder($utilisateur)
                ->add('username', 'text', array(
                    'disabled' => true
                ))
                ->add('role', 'choice', array(
                    'choices' => $utilisateur->getProfile(),
                    'label' => 'Rôle de l\'utilisateur : '
                ))        
                ->add('valider', 'submit')
                ->getForm();
        $formModifUtilisateur->handleRequest($this->get('request'));
        if($formModifUtilisateur->isValid()){ 
            $em = $this->getDoctrine()->getManager();
            $utilisateur->setRole($formModifUtilisateur->get('role')->getData());
            $em->persist($utilisateur);
            $em->flush();
            
            return $this->redirect($this->generateUrl('admin_utilisateur'));
        }
        return array('utilisateur' => $utilisateur, 'form' => $formModifUtilisateur->createView());
    }
    
    /**
    * @Template()
    */
    public function supprimerUtilisateurAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('BricoEchangeFrontBundle:Utilisateur');
        $utilisateur = $rep->find($id);
        $em->remove($utilisateur);
        $em->flush();
        return $this->redirect($this->generateUrl('admin_utilisateur'));
    }
  
    /**
    * @Template()
    */
    public function modifierAnnonceAction($id)
    {  
        if (!$id){
            throw $this->createNotFoundException('Aucune annonce trouvée');
        }
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('BricoEchangeFrontBundle:Annonce');
        $annonce = $rep->find($id);
        //$annonce->setPhoto(new File($this->getParameter('images_annonce').$annonce->getPhoto()));
        //var_dump($annonce->getPhoto());
        $formModifAnnonce = $this->createFormBuilder($annonce)
                ->add('type', 'entity', array(
                    'class' => 'BricoEchangeFrontBundle:Type',
                    'query_builder' => function(EntityRepository $rep){
                        return $rep->createQueryBuilder('t');
                    },
                    'property' => 'libelle',
                    'label' => 'Type d\'annonce : '
                ))
                ->add('categorie', 'entity', array(
                    'class' => 'BricoEchangeFrontBundle:Categorie',
                    'query_builder' => function(EntityRepository $rep){
                        return $rep->createQueryBuilder('c');
                    },
                    'property' => 'libelle',
                    'label' => 'Catégorie : '
                ))
                ->add('resume', 'text', array(
                    'constraints'=> array(new NotBlank(), new Length(array(
                        'min'        => 5,
                        'max'        => 50,
                        'minMessage' => 'Le résumé de l\'annonce doit comporter au minimum {{ limit }} caractères',
                        'maxMessage' => 'Le résumé de l\'annonce doit comporter au maximum {{ limit }} caractères',

                    ))),
                    'label' => 'Résumé de l\'annonce : '
                ))
                ->add('marque', 'text', array(
                    'constraints'=> array(new NotBlank(), new Length(array(
                        'min'        => 5,
                        'max'        => 100,
                        'minMessage' => 'La marque doit comporter au minimum {{ limit }} caractères',
                        'maxMessage' => 'La marque doit comporter au maximum {{ limit }} caractères',
                    ))),
                    'label' => 'Marque : '                   
                ))
                ->add('description', 'textarea', array(
                    'constraints'=> array(new NotBlank(), new Length(array(
                        'min'        => 20,
                        'max'        => 255,
                        'minMessage' => 'L\'annonce doit comporter au minimum {{ limit }} caractères',
                        'maxMessage' => 'L\'annonce doit comporter au maximum {{ limit }} caractères',
                    ))),
                    'label' => 'Description de l\'annonce : '                   
                ))
                /*->add('photo', 'file', array(
                    'constraints'=> array(
                        new NotBlank(), 
                        new Assert\File()
                    ),
                    'label'=>'Image du produit : '
                ))*/
                ->add('email', 'text', array(
                    'constraints'=> array(
                        new NotBlank(), 
                        new Length(array(
                            'min'        => 5,
                            'max'        => 100,
                            'minMessage' => 'L\'email doit comporter au minimum {{ limit }} caractères',
                            'maxMessage' => 'L\'email doit comporter au maximum {{ limit }} caractères',
                        ))
                    ),
                    'label' => 'Email : '
                ))
                ->add('ville', 'text', array(
                    'constraints'=> array(
                        new NotBlank(), 
                        new Length(array(
                            'min'        => 2,
                            'max'        => 100,
                            'minMessage' => 'La ville doit comporter au minimum {{ limit }} caractères',
                            'maxMessage' => 'La ville doit comporter au maximum {{ limit }} caractères',
                        ))
                    ),
                    'label' => 'Ville : ',
                ))                
                ->add('valider', 'submit')
                ->getForm();
        $formModifAnnonce->handleRequest($this->get('request'));
        if($formModifAnnonce->isValid()){ 
            $em = $this->getDoctrine()->getManager();
            /** @var UploadedFile $file */
            /*$file = $annonce->getPhoto();
            $fileName = $file->getClientOriginalName();
            $file->move($this->getParameter('images_annonce'), $fileName);
            ->setPhoto($fileName)*/
            
            $annonce->setDescription($formModifAnnonce->get('description')->getData())
                    ->setResume($formModifAnnonce->get('resume')->getData())
                    ->setEmail($formModifAnnonce->get('email')->getData())
                    ->setVille($formModifAnnonce->get('ville')->getData())
                    ->setMarque($formModifAnnonce->get('marque')->getData())
                    ->setReprise(false);
            $em->persist($annonce);
            $em->flush();
            
            return $this->redirect($this->generateUrl('annonce', array('id' =>$id)));
        }
        return array('annonce' => $annonce, 'form' => $formModifAnnonce->createView());
    }
    
    /**
    * @Template()
    */
    public function supprimerCategorieAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repCateg = $em->getRepository('BricoEchangeFrontBundle:Categorie');
        $repAnnonce = $em->getRepository('BricoEchangeFrontBundle:Annonce');
        $annonces = $repAnnonce->findAll();       
        $categorie = $repCateg->find($id);
        $categorieUtilisee = false;
        foreach ($annonces as $value) {
            if ($value->getCategorie() == $categorie){
               $categorieUtilisee = true;
            }
        }
        if ($categorieUtilisee == false){
            $em->remove($categorie);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('categorie'));
    }
    
    
    /**
    * @Template()
    */
    public function supprimerAnnonceAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('BricoEchangeFrontBundle:Annonce');
        $annonce = $rep->find($id);
        $em->remove($annonce);
        $em->flush();
        return $this->redirect($this->generateUrl('admin_annonce'));
    }
    
    
    
    /**
    * @Template()
    */
    public function ajoutCategorieAction()
    {      
        $categorie = new Categorie();
        $formAjoutCategorie = $this->createFormBuilder($categorie)
                ->add('libelle', 'text', array(
                    'constraints'=> array(new NotBlank(), new Length(array(
                        'min'        => 5,
                        'max'        => 100,
                        'minMessage' => 'La catégorie doit comporter au minimum {{ limit }} caractères',
                        'maxMessage' => 'La marque doit comporter au maximum {{ limit }} caractères',
                    ))),
                    'label' => 'Libellé catégorie : '                  
                ))
                ->add('valider', 'submit')
                ->getForm();
        $formAjoutCategorie->handleRequest($this->get('request'));
        if($formAjoutCategorie->isValid()){ 
            $em = $this->getDoctrine()->getManager();
            $categorie->setLibelle($formAjoutCategorie->get('libelle')->getData());
            $em->persist($categorie);
            $em->flush();
            
            return $this->redirect($this->generateUrl('categorie'));
        }
        return array('categorie' => $categorie, 'form' => $formAjoutCategorie->createView());
    }
    
    /**
    * @Template()
    */
    public function modifierCategorieAction($id)
    {  
        if (!$id){
            throw $this->createNotFoundException('Aucune catégorie trouvée');
        }
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('BricoEchangeFrontBundle:Categorie');
        $categorie = $rep->find($id);
        $formModifCategorie = $this->createFormBuilder($categorie)
                ->add('libelle', 'text', array(
                    'constraints'=> array(new NotBlank(), new Length(array(
                        'min'        => 5,
                        'max'        => 100,
                        'minMessage' => 'La catégorie doit comporter au minimum {{ limit }} caractères',
                        'maxMessage' => 'La marque doit comporter au maximum {{ limit }} caractères',
                    ))),
                    'label' => 'Libellé catégorie : '                  
                ))
                ->add('valider', 'submit')
                ->getForm();
        $formModifCategorie->handleRequest($this->get('request'));
        if($formModifCategorie->isValid()){ 
            $em = $this->getDoctrine()->getManager();
            $categorie->setLibelle($formModifCategorie->get('libelle')->getData());
            $em->persist($categorie);
            $em->flush();
            
            return $this->redirect($this->generateUrl('categorie'));
        }
        return array('categorie' => $categorie, 'form' => $formModifCategorie->createView());
    }
}