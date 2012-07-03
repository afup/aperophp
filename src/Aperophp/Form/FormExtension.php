<?php

namespace Aperophp\Form;

use Symfony\Component\Form\AbstractExtension;
use Doctrine\DBAL\Connection;
use Aperophp\Form\Type;

class FormExtension extends AbstractExtension
{
    protected $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * {@inheritDoc}
     */
    protected function loadTypes()
    {
        return array(
            new Type\DrinkCommentType(),
            new Type\DrinkParticipationAnonymousEditType(),
            new Type\DrinkParticipationType(),
            new Type\DrinkType($this->connection),
            new Type\EditMemberType(),
            new Type\SigninType(),
            new Type\SignupType(),
        );
    }
}
