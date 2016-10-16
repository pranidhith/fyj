<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * TrainSchedule
 *
 * @ORM\Table(name="train_schedule")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TrainScheduleRepository")
 */
class TrainSchedule
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="tripNo", type="string", length=12)
     */
    private $tripNo;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="start", type="string", length=255)
     */
    private $start;

    /**
     * @var string
     *
     * @ORM\Column(name="departureTime", type="string", length=255)
     */
    private $departureTime;

    /**
     * @var string
     *
     * @ORM\Column(name="end", type="string", length=255)
     */
    private $destination;

    /**
     * @var string
     *
     * @ORM\Column(name="arrivalTime", type="string", length=255)
     */
    private $arrivalTime;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

     /**
     * Set tripNo
     *
     * @param string $name
     *
     * @return Field
     */
    public function setTripNo($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get tripNo
     *
     * @return string
     */
    public function getTripNo()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Field
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
     * Set description
     *
     * @param string $description
     *
     * @return Field
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }



    /**
     * Set start
     *
     * @param string $start
     *
     * @return TrainSchedule
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return string
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set departureTime
     *
     * @param string $departureTime
     *
     * @return TrainSchedule
     */
    public function setDepartureTime($departureTime)
    {
        $this->departureTime = $departureTime;

        return $this;
    }

    /**
     * Get departureTime
     *
     * @return string
     */
    public function getDepartureTime()
    {
        return $this->departureTime;
    }

    /**
     * Set destination
     *
     * @param string $destination
     *
     * @return TrainSchedule
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set arrivalTime
     *
     * @param string $arrivalTime
     *
     * @return TrainSchedule
     */
    public function setArrivalTime($arrivalTime)
    {
        $this->arrivalTime = $arrivalTime;

        return $this;
    }

    /**
     * Get arrivalTime
     *
     * @return string
     */
    public function getArrivalTime()
    {
        return $this->arrivalTime;
    }
}
