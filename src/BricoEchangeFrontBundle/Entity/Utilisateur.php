<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BricoEchangeFrontBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Description de la classe Categorie
 *
 * @author avasnier
 * @ORM\Entity
 * @ORM\Table(name="utilisateur")
 */
class Utilisateur implements UserInterface, Serializable{
    
    const ADMIN = "admin";
    const UTILISATEUR = "utilisateur";
    const PARTENAIRE = "partenaire";
    
    static function getProfile(){
        return array(
            self::ADMIN => self::ADMIN,
            self::UTILISATEUR => self::UTILISATEUR,
            self::PARTENAIRE => self::PARTENAIRE
        );
    }
    /**
     * @ORM\Id
     * @ORM\Column(name="id",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $username;
    /**
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @ORM\Column(type="string")
     */
    private $nom;
    /**
     * @ORM\Column(type="string")
     */
    private $prenom;
    /**
     * @ORM\Column(type="string")
     */
    private $email;
    /**
     * @ORM\Column(type="string")
     */
    private $role;    
    /**
     * @ORM\Column(type="string")
     */
    private $siteWeb;
    /**
     * @ORM\OneToMany(targetEntity="Annonce",mappedBy="utilisateur",cascade={"persist","remove"})
     */
    private $annonces;
    
    public function __construct() {
        $this->annonces = new ArrayCollection();
    }
    
    function getId() {
        return $this->id;
    }

    function getLibelle() {
        return $this->libelle;
    }

    function getAnnonces() {
        return $this->annonces;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setLibelle($libelle) {
        $this->libelle = $libelle;
        return $this;
    }

    function setAnnonces($annonces) {
        $this->annonces = $annonces;
        return $this;
    }
    function getUsername() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }

    function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    function setPassword($password) {
        $this->password = $password;
        return $this;
    }
    function getNom() {
        return $this->nom;
    }

    function getPrenom() {
        return $this->prenom;
    }

    function getEmail() {
        return $this->email;
    }

    function getSiteWeb() {
        return $this->siteWeb;
    }
    
    function getRole() {
        return $this->role;
    }

    function setNom($nom) {
        $this->nom = $nom;
        return $this;
    }

    function setPrenom($prenom) {
        $this->prenom = $prenom;
        return $this;
    }

    function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    function setSiteWeb($siteWeb) {
        $this->siteWeb = $siteWeb;
        return $this;
    }
    
    function setRole($role) {
        $this->role = $role;
        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password
            // see section on salt below
            // $this->salt,
        ));
    }
    
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password
            // see section on salt below
            // $this->salt
            ) = unserialize($serialized);
        // TODO: Implement unserialize() method.
    }
    
    public function eraseCredentials() {
        
    }

    public function getRoles() {
        if($this->role == "admin"){
            return array('ROLE_ADMIN');
        }elseif ($this->role == "partenaire"){
            return array('ROLE_PARTENAIRE');
        }else{
            return array('ROLE_USER');
        }
        
    }

    public function getSalt() {
        return null;
    }

}
