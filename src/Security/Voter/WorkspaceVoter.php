<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class WorkspaceVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        return in_array($attribute, ['WORKSAPCE_EDIT', 'WORKSAPCE_DELETE'])
            && $subject instanceof \App\Entity\Chat\Workspace;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return $subject->isOwner($user);
    }
}
