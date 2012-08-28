<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace DoctrineModule\Options;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Zend\Authentication\Adapter\Exception;
use Zend\Stdlib\AbstractOptions;

/**
 * This options class is used by both DoctrineModule\Authentication\Adapter\ObjectRepository
 * and DoctrineModule\Service\AuthenticationAdapterFactory.
 *
 * When using with DoctrineModule\Authentication\Adapter\ObjectRepository the following
 * options are required:
 *
 * $identityProperty
 * $credentialProperty
 *
 * In addition either $objectRepository or $objectManager and $identityClass must be set.
 * If $objectRepository is set, it takes precedence over $objectManager and $identityClass.
 * If $objectManager is used, it must be an instance of ObjectManager.
 *
 * All remains the same using with DoctrineModule\Service\AuthenticationAdapterFactory,
 * however, a string may be passed to $objectManager. This string must be a valid key to
 * retrieve an ObjectManager instance from the ServiceManager.
 *
 * @license MIT
 * @link    http://www.doctrine-project.org/
 * @since   0.5.0
 * @author  Michaël Gallego <mic.gallego@gmail.com>
 */
class AuthenticationAdapter extends AbstractOptions
{
    /**
     * A valid object implementing ObjectManager interface
     *
     * @var string | ObjectManager
     */
    protected $objectManager;

    /**
     * A valid object implementing ObjectRepository interface (or ObjectManager/identityClass)
     *
     * @var ObjectRepository
     */
    protected $objectRepository;

    /**
     * Entity's class name
     *
     * @var string
     */
    protected $identityClass;

    /**
     * Property to use for the identity
     *
     * @var string
     */
    protected $identityProperty;

    /**
     * Property to use for the credential
     *
     * @var string
     */
    protected $credentialProperty;

    /**
     * Callable function to check if a credential is valid
     *
     * @var mixed
     */
    protected $credentialCallable;


    /**
     * @param  string | ObjectManager $objectManager
     * @return Authentication
     */
    public function setObjectManager($objectManager)
    {
        $this->objectManager = $objectManager;
        return $this;
    }

    /**
     * @return ObjectManager
     */
    public function getObjectManager()
    {
        return $this->objectManager;
    }

    /**
     * @param  ObjectRepository $objectRepository
     * @return Authentication
     */
    public function setObjectRepository(ObjectRepository $objectRepository)
    {
        $this->objectRepository = $objectRepository;
        return $this;
    }

    /**
     * @return ObjectRepository
     */
    public function getObjectRepository()
    {
        if ($this->objectRepository) {
            return $this->objectRepository;
        }

        return $this->objectManager->getRepository($this->identityClass);
    }

    /**
     * @param string $identityClass
     * @return Authentication
     */
    public function setIdentityClass($identityClass)
    {
        $this->identityClass = $identityClass;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentityClass()
    {
        return $this->identityClass;
    }

    /**
     * @param  string $identityProperty
     * @throws Exception\InvalidArgumentException
     * @return Authentication
     */
    public function setIdentityProperty($identityProperty)
    {
        if (!is_string($identityProperty) || $identityProperty === '') {
            throw new Exception\InvalidArgumentException(sprintf(
                'Provided $identityProperty is invalid, %s given',
                gettype($identityProperty)
            ));
        }

        $this->identityProperty = $identityProperty;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentityProperty()
    {
        return $this->identityProperty;
    }

    /**
     * @param  string $credentialProperty
     * @throws Exception\InvalidArgumentException
     * @return Authentication
     */
    public function setCredentialProperty($credentialProperty)
    {
        if (!is_string($credentialProperty) || $credentialProperty === '') {
            throw new Exception\InvalidArgumentException(sprintf(
                'Provided $credentialProperty is invalid, %s given',
                gettype($credentialProperty)
            ));
        }

        $this->credentialProperty = $credentialProperty;

        return $this;
    }

    /**
     * @return string
     */
    public function getCredentialProperty()
    {
        return $this->credentialProperty;
    }

    /**
     * @param  mixed $credentialCallable
     * @throws Exception\InvalidArgumentException
     * @return Authentication
     */
    public function setCredentialCallable($credentialCallable)
    {
        if (!is_callable($credentialCallable)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '"%s" is not a callable',
                is_string($credentialCallable) ? $credentialCallable : gettype($credentialCallable)
            ));
        }

        $this->credentialCallable = $credentialCallable;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCredentialCallable()
    {
        return $this->credentialCallable;
    }
}