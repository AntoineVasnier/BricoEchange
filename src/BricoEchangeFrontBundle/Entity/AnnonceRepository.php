<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BricoEchangeFrontBundle\Entity;

use Doctrine\ORM\EntityRepository;

class AnnonceRepository extends EntityRepository
{
    public function getLastAnnonces($nbAnnonces)
    {
        $request = 'SELECT a
            FROM BricoEchangeFrontBundle:Annonce a
            ORDER BY a.datePublication DESC';
        $query = $this->getEntityManager()->createQuery($request);
        $query->setFirstResult(0);
        $query->setMaxResults($nbAnnonces);
        return $query->getResult();
    }
    
    public function getAnnoncesCateg($categorie)
    {   
        $request = 'SELECT a
            FROM BricoEchangeFrontBundle:Annonce a 
            WHERE a.categorie = :categorie
            ORDER BY a.datePublication DESC';
        $query = $this->getEntityManager()->createQuery($request);
        $query->setParameter('categorie', $categorie);
        return $query->getResult();
    }
}
