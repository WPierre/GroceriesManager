<?php

namespace WPierre\GroceriesManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * GroceriesList
 *
 * @ORM\Table(name="groceries_list")
 * @ORM\Entity
 */
class GroceriesList
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="string", length=2000, nullable=true)
     */
    private $commentaire;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="WPierre\GroceriesManagerBundle\Entity\Item", cascade={"persist"})
     */
	private $items;

	/**
	 * @var datetime $created
	 *
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime")
	 */
	private $created;
	
	/**
	 * @var datetime $updated
	 *
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime")
	 */
	private $updated;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return GroceriesList
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return GroceriesList
     */
    public function setCommentaire($commentaire)
    {
    	$this->commentaire = $commentaire;
    
    	return $this;
    }
    
    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
    	return $this->commentaire;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return GroceriesList
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return GroceriesList
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Add items
     *
     * @param \WPierre\GroceriesManagerBundle\Entity\Item $items
     * @return GroceriesList
     */
    public function addItem(\WPierre\GroceriesManagerBundle\Entity\Item $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \WPierre\GroceriesManagerBundle\Entity\Item $items
     */
    public function removeItem(\WPierre\GroceriesManagerBundle\Entity\Item $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Remove all items
     *
     * 
     */
    public function removeAllItems()
    {
    	$this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    
    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }
    
    /**
     * Returns true if selected item is in the list
     * @param \WPierre\GroceriesManagerBundle\Entity\Item $item
     * @return boolean
     */
    public function hasItem(\WPierre\GroceriesManagerBundle\Entity\Item $item){
    	return $this->items->contains($item);
    }
}
