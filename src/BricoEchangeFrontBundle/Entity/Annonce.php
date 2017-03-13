<?php

namespace BricoEchangeFrontBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Description de la classe Annonce
 *
 * @author avasnier
 * @ORM\Entity(
 *  repositoryClass="BricoEchangeFrontBundle\Entity\AnnonceRepository"
 * )
 * @ORM\Table(name="annonce")
 */
class Annonce {
    /**
     * @ORM\Id
     * @ORM\Column(name="id",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string",length=255)
     */
    private $description;
    /**
     * @ORM\Column(type="string",length=100)
     */
    private $resume;
    /**
     * @ORM\Column(type="string",length=100)
     */
    private $photo;
    /**
     * @ORM\Column(type="string",length=100)
     */
    private $email;
    /**
     * @ORM\Column(type="string",length=100)
     */
    private $ville;
    /**
     * @ORM\Column(type="string",length=100)
     */
    private $marque;
    /**
     * @ORM\Column(type="boolean")
     */
    private $reprise;
    /**
     * @ORM\Column(type="date")
     */
    private $datePublication;
    
    /**
     * @ORM\ManyToOne(targetEntity="Type",inversedBy="annonces")
     */
    private $type;
    
    /**
     * @ORM\ManyToOne(targetEntity="Categorie",inversedBy="annonces")
     */
    private $categorie;
    
    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur",inversedBy="annonces")
     */
    private $utilisateur;
    
    public function getId()
    {
        return $this->id;
    }
    
    function getDescription() {
        return $this->description;
    }

    function getResume() {
        return $this->resume;
    }

    function getPhoto() {
        return $this->photo;
    }

    function getEmail() {
        return $this->email;
    }

    function getVille() {
        return $this->ville;
    }

    function getMarque() {
        return $this->marque;
    }

    function getReprise() {
        return $this->reprise;
    }

    function getType() {
        return $this->type;
    }
    
    function getDatePublication(){
        return $this->datePublication;
    }
    
    public function getCategorie()
    {
        return $this->categorie;
    }
    
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
    
    function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    function setResume($resume) {
        $this->resume = $resume;
        return $this;
    }

    function setPhoto($photo) {
        $this->photo = $photo;
        return $this;
    }

    function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    function setVille($ville) {
        $this->ville = $ville;
        return $this;
    }

    function setMarque($marque) {
        $this->marque = $marque;
        return $this;
    }

    function setReprise($reprise) {
        $this->reprise = $reprise;
        return $this;
    }

    function setType($type) {
        $this->type = $type;
        return $this;
    }
    
    function setDatePublication($datePublication){
        $this->datePublication = $datePublication;
        return $this;
    }
    
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }
    
    public function setUtilisateur($utilisateur)
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }
}
