<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LikeNotificationRepository")
 */
class LikeNotification extends Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\MicroPost")
     */
    private $micropost;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $likedBy;

    /**
     * @return mixed
     */
    public function getMicropost()
    {
        return $this->micropost;
    }

    /**
     * @param mixed $micropost
     * @return LikeNotification
     */
    public function setMicropost($micropost)
    {
        $this->micropost = $micropost;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLikedBy()
    {
        return $this->likedBy;
    }

    /**
     * @param mixed $likedBy
     * @return LikeNotification
     */
    public function setLikedBy($likedBy)
    {
        $this->likedBy = $likedBy;
        return $this;
    }
}
