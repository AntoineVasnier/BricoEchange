<?php

namespace BricoEchangeFrontBundle\Controller;

use BricoEchangeFrontBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repAnnonce = $em->getRepository('BricoEchangeFrontBundle:Annonce');
        $annonces = $repAnnonce->getLastAnnonces(10);
        $repCateg =  $em->getRepository('BricoEchangeFrontBundle:Categorie');
        $categories = $repCateg->findAll();
        return $this->render('BricoEchangeFrontBundle:Default:index.html.twig', array('annonces' => $annonces, 'categories' => $categories));
    }
    
    public function connexionAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('BricoEchangeFrontBundle:Default:connexion.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        )); 
    }
    
    public function creerCompteAction(Request $request){
        if (empty($request->request->all())){
            return $this->render('BricoEchangeFrontBundle:Default:creationCompte.html.twig');
        }else{
            $utilisateur = new Utilisateur();
            $utilisateur->setUsername($request->request->get('username'))
                    ->setPassword($request->request->get('password'))
                    ->setNom($request->request->get('nom'))
                    ->setPrenom($request->request->get('prenom'))
                    ->setEmail($request->request->get('email'))
                    ->setSiteWeb($request->request->get('siteWeb'))
                    ->setRole('utilisateur');
            $password = $this->get('security.password_encoder')->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($password);
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();
            return $this->redirect($this->generateUrl('connexion'));
        }
    }
    
    public function compteAction(){
        return new Response('Non implémenté');
    }
}
