<?php

namespace App\Twig;

use Symfony\Component\Workflow\Registry;

class WorkflowExtension extends \Twig_Extension
{
    private $workflowRegistry;

    public function __construct(Registry $workflowRegistry)
    {
        $this->workflowRegistry = $workflowRegistry;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('workflow_all_transitions', [$this, 'getTransitions']),
            new \Twig_SimpleFunction('workflow_build_transition_blocker_list', [$this, 'buildTransitionBlockerList']),
        );
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('bool2string', [$this, 'boolToString']),
        ];
    }



    // This method is a hack to get all transitions, enabled or not.
    // This should be done only for a demo purpose
    public function getTransitions($subject, string $name = null)
    {
        try {
            $workflow = $this->workflowRegistry->get($subject, $name);
        } catch (\Throwable $t) {
            throw new \RuntimeException(sprintf('%s Have you added the "supports" config key to your Yaml configuration? If you are using PHP configuration you need to add your StateMachine/Workflow to a "Registry".', $t->getMessage()), 0, $t);
        }

        return $workflow->getDefinition()->getTransitions();
    }

    public function buildTransitionBlockerList($subject, string $transitionName, string $name = null)
    {
        $workflow = $this->workflowRegistry->get($subject, $name);

        return $workflow->buildTransitionBlockerList($subject, $transitionName);
    }


    public function boolToString($bool)
    {
        if ($bool) {
            return 'true';
        }

        return 'false';
    }

    public function getName()
    {
        return 'workflow_developer';
    }
}
