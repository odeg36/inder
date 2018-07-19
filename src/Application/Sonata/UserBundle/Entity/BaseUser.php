<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Sonata\UserBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Sonata\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\GroupableInterface;
use FOS\UserBundle\Model\GroupInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * BaseUser
 */
abstract class BaseUser implements UserInterface, GroupableInterface {

    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="username", length=255, nullable=false)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string", name="username_canonical", length=255, nullable=false)
     */
    protected $usernameCanonical;

    /**
     * @var string
     * @ORM\Column(type="string", name="email", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", name="email_canonical", length=255, nullable=true)
     */
    protected $emailCanonical;

    /**
     * @var string
     * @ORM\Column(type="string", name="nombre", length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @var string
     * @ORM\Column(type="string", name="apellido", length=255, nullable=true)
     */
    protected $lastname;

    /**
     * @var string
     * @ORM\Column(type="string", name="genero", length=1, nullable=true)
     */
    protected $gender;

    /**
     * @var string
     * @ORM\Column(type="string", name="telefono", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", name="habilitado", nullable=false)
     */
    protected $enabled;

    /**
     * The salt to use for hashing
     *
     * @var string
     * @ORM\Column(type="string", name="salt", length=255, nullable=true)
     */
    protected $salt;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     * @ORM\Column(type="string", name="password", length=255, nullable=true)
     */
    protected $password;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     * @ORM\Column(type="string", name="plain_password", length=255, nullable=true)
     */
    protected $plainPassword;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="ultimo_inicio_sesion", nullable=true)
     */
    protected $lastLogin;

    /**
     * Random string sent to the user email address in order to verify it
     *
     * @var string
     * @ORM\Column(type="string", name="token_confirmacion", nullable=true)
     */
    protected $confirmationToken;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="password_requested_at", nullable=true)
     */
    protected $passwordRequestedAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", name="fecha_nacimiento", nullable=true)
     */
    protected $dateOfBirth;

    /**
     * @var Collection
     */
    protected $groups;

    /**
     * @var array
     * @ORM\Column(type="array", name="roles",nullable=false)
     */
    protected $roles;

    /**
     * @var string
     * @ORM\Column(type="string", name="token", length=255, nullable=true)
     */
    protected $token;

    public function __construct() {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->enabled = false;
        $this->roles = array();
    }

    public function addRole($role) {
        $role = strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
        return $this;
    }

    public function isAccountNonExpired() {
        return true;
    }

    public function isAccountNonLocked() {
        return true;
    }

    public function isCredentialsNonExpired() {
        return true;
    }

    /**
     * Serializes the user.
     *
     * The serialized data have to contain the fields used by the equals method and the username.
     * 	
     * @return string
     */
    public function serialize() {
        return serialize(array(
            $this->password,
            $this->salt,
            $this->usernameCanonical,
            $this->username,
            $this->enabled,
            $this->id,
        ));
    }

    /**
     * Unserializes the user.
     *
     * @param string $serialized
     */
    public function unserialize($serialized) {
        $data = unserialize($serialized);
// add a few extra elements in the array to ensure that we have enough keys whe n unserializing
// older data which does not include all properties.
        $data = array_merge($data, array_fill(0, 2, null));
        list(
                $this->password,
                $this->salt,
                $this->usernameCanonical,
                $this->username,
                $this->enabled,
                $this->id
                ) = $data;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials() {
        $this->plainPassword = null;
    }

    /**
     * Returns the user unique id.
     *
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getUsernameCanonical() {
        return $this->usernameCanonical;
    }

    public function getSalt() {
        return $this->salt;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getEmailCanonical() {
        return $this->emailCanonical;
    }

    /**
     * Gets the encrypted password.
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    public function getPlainPassword() {
        return $this->plainPassword;
    }

    /**
     * Gets the last login time.
     *
     * @return \DateTime
     */
    public function getLastLogin() {
        return $this->lastLogin;
    }

    public function getConfirmationToken() {
        return $this->confirmationToken;
    }

    /**
     * Returns the user roles
     *
     * @return array The roles
     */
    public function getRoles() {
        $roles = $this->roles;
        foreach ($this->getGroups() as $group) {
            $roles = array_merge($roles, $group->getRoles());
        }
// we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;
        return array_unique($roles);
    }

    /**
     * Never use this to check if this user has access to anything!
     *
     * Use the SecurityContext, or an implementation of AccessDecisionManager
     * instead, e.g.
     *
     *         $securityContext->isGranted('ROLE_USER');
     *
     * @param string $role
     *
     * @return boolean
     */
    public function hasRole($role) {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function isEnabled() {
        return $this->enabled;
    }

    public function isSuperAdmin() {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }

    public function removeRole($role) {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }
        return $this;
    }

    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    public function setUsernameCanonical($usernameCanonical) {
        $this->usernameCanonical = $usernameCanonical;
        return $this;
    }

    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    public function setEmailCanonical($emailCanonical) {
        $this->emailCanonical = $emailCanonical;
        return $this;
    }

    public function setEnabled($boolean) {
        $this->enabled = (Boolean) $boolean;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    public function setSuperAdmin($boolean) {
        if (true === $boolean) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }
        return $this;
    }

    public function setPlainPassword($password) {
        $this->plainPassword = $password;
        return $this;
    }

    public function setLastLogin(\DateTime $time = null) {
        $this->lastLogin = $time;
        return $this;
    }

    public function setConfirmationToken($confirmationToken) {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    public function setPasswordRequestedAt(\DateTime $date = null) {
        $this->passwordRequestedAt = $date;
        return $this;
    }

    /**
     * Gets the timestamp that the user requested a password reset.
     *
     * @return null|\DateTime
     */
    public function getPasswordRequestedAt() {
        return $this->passwordRequestedAt;
    }

    public function isPasswordRequestNonExpired($ttl) {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
                $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    public function setRoles(array $roles) {
        $this->roles = array();
        foreach ($roles as $role) {
            $this->addRole($role);
        }
        return $this;
    }

    /**
     * Gets the groups granted to the user.
     *
     * @return Collection
     */
    public function getGroups() {
        return $this->groups ?: $this->groups = new ArrayCollection();
    }

    public function getGroupNames() {
        $names = array();
        foreach ($this->getGroups() as $group) {
            $names[] = $group->getName();
        }
        return $names;
    }

    /**
     * @param string $name
     *
     * @return boolean
     */
    public function hasGroup($name) {
        return in_array($name, $this->getGroupNames());
    }

    public function addGroup(GroupInterface $group) {
        if (!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }
        return $this;
    }

    public function removeGroup(GroupInterface $group) {
        if ($this->getGroups()->contains($group)) {
            $this->getGroups()->removeElement($group);
        }
        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled() {
        return $this->enabled;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return BaseUser
     */
    public function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    /**
     * Returns the gender list.
     *
     * @return array
     */
    public static function getGenderList() {
        return array(
            'gender_female' => UserInterface::GENDER_FEMALE,
            'gender_male' => UserInterface::GENDER_MALE,
        );
    }

    /**
     * Returns a string representation.
     *
     * @return string
     */
    public function __toString() {
        return $this->getUsername() ?: '-';
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt = null) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setUpdatedAt(\DateTime $updatedAt = null) {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    public function setGroups($groups) {
        foreach ($groups as $group) {
            $this->addGroup($group);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setTwoStepVerificationCode($twoStepVerificationCode) {
        $this->twoStepVerificationCode = $twoStepVerificationCode;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTwoStepVerificationCode() {
        return $this->twoStepVerificationCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setBiography($biography) {
        $this->biography = $biography;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBiography() {
        return $this->biography;
    }

    /**
     * {@inheritdoc}
     */
    public function setDateOfBirth($dateOfBirth) {
        $this->dateOfBirth = $dateOfBirth;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getDateOfBirth() {
        return $this->dateOfBirth;
    }

    /**
     * {@inheritdoc}
     */
    public function setFacebookData($facebookData) {
        $this->facebookData = $facebookData;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFacebookData() {
        return $this->facebookData;
    }

    /**
     * {@inheritdoc}
     */
    public function setFacebookName($facebookName) {
        $this->facebookName = $facebookName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFacebookName() {
        return $this->facebookName;
    }

    /**
     * {@inheritdoc}
     */
    public function setFacebookUid($facebookUid) {
        $this->facebookUid = $facebookUid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFacebookUid() {
        return $this->facebookUid;
    }

    /**
     * {@inheritdoc}
     */
    public function setFirstname($firstname) {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getFirstname() {
        return $this->firstname;
    }

    /**
     * {@inheritdoc}
     */
    public function setGender($gender) {
        $this->gender = $gender;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGender() {
        return $this->gender;
    }

    /**
     * {@inheritdoc}
     */
    public function setGplusData($gplusData) {
        $this->gplusData = $gplusData;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGplusData() {
        return $this->gplusData;
    }

    /**
     * {@inheritdoc}
     */
    public function setGplusName($gplusName) {
        $this->gplusName = $gplusName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGplusName() {
        return $this->gplusName;
    }

    /**
     * {@inheritdoc}
     */
    public function setGplusUid($gplusUid) {
        $this->gplusUid = $gplusUid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getGplusUid() {
        return $this->gplusUid;
    }

    /**
     * {@inheritdoc}
     */
    public function setLastname($lastname) {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLastname() {
        return $this->lastname;
    }

    /**
     * {@inheritdoc}
     */
    public function setLocale($locale) {
        $this->locale = $locale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLocale() {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    public function setPhone($phone) {
        $this->phone = $phone;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPhone() {
        return $this->phone;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimezone($timezone) {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimezone() {
        return $this->timezone;
    }

    /**
     * {@inheritdoc}
     */
    public function setTwitterData($twitterData) {
        $this->twitterData = $twitterData;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTwitterData() {
        return $this->twitterData;
    }

    /**
     * {@inheritdoc}
     */
    public function setTwitterName($twitterName) {
        $this->twitterName = $twitterName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTwitterName() {
        return $this->twitterName;
    }

    /**
     * {@inheritdoc}
     */
    public function setTwitterUid($twitterUid) {
        $this->twitterUid = $twitterUid;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTwitterUid() {
        return $this->twitterUid;
    }

    /**
     * {@inheritdoc}
     */
    public function setWebsite($website) {
        $this->website = $website;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getWebsite() {
        return $this->website;
    }

    /**
     * {@inheritdoc}
     */
    public function setToken($token) {
        $this->token = $token;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function getFullname() {
        return sprintf('%s %s', $this->getFirstname(), $this->getLastname());
    }

    /**
     * {@inheritdoc}
     */
    public function getRealRoles() {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    public function setRealRoles(array $roles) {
        $this->setRoles($roles);

        return $this;
    }

}
