<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class WorkspaceActiveVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['WORKSPACE_ACTIVE']);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return $token->getAttribute('workspace_id') != null;
    }
}
