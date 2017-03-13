<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BricoEchangeFrontBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 * Description de la classe Categorie
 *
 * @author avasnier
 * @ORM\Entity
 * @ORM\Table(name="categorie")
 */
class Categorie {
    /**
     * @ORM\Id
     * @ORM\Column(name="id",type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $libelle;
    /**
     * @ORM\OneToMany(targetEntity="Annonce",mappedBy="categorie")
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


}
