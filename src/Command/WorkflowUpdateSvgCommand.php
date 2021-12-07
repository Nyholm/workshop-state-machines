<?php

namespace App\Command;

use App\Entity\Article;
use App\Entity\PullRequest;
use App\Entity\TrafficLight;
use App\Entity\UserProfile;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Workflow\Dumper\GraphvizDumper;
use Symfony\Component\Workflow\Dumper\StateMachineGraphvizDumper;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\StateMachine;

class WorkflowUpdateSvgCommand extends Command
{
    private $registry;
    private $projectDir;

    public function __construct(Registry $registry, $projectDir)
    {

        parent::__construct();
        $this->registry = $registry;
        $this->projectDir = $projectDir;
    }


    protected function configure()
    {
        $this
            ->setName('app:build:svg')
            ->setDescription('Build the SVG')
            ->addArgument('service_name', InputArgument::REQUIRED, 'The service name of the workflow (ex workflow.article)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('service_name');
        $shortName = explode('.', $name)[1];

        $map = [
            'traffic_light' => new TrafficLight(),
            'traffic_light_php' => new TrafficLight(),
            'article' => new Article(),
            'pull_request' => new PullRequest('Foobar'),
            'signup' => new UserProfile(),
        ];

        $workflow = $this->registry->get($map[$shortName] ?? null, $shortName);

        if ($workflow === null) {
            $output->writeln('Cannot find workflow with name: '.$name);
            return 1;
        }

        $definition = $workflow->getDefinition();

        $dumper = new GraphvizDumper();
        if ($workflow instanceof StateMachine) {
            $dumper = new StateMachineGraphvizDumper();
        }

        $dot = $dumper->dump($definition, null, ['node' => ['width' => 1.6]]);

        $process = new Process(['dot', '-Tsvg']);
        $process->setInput($dot);
        $process->mustRun();

        $svg = $process->getOutput();

        $svg = preg_replace('/.*<svg/ms', sprintf('<svg class="img-responsive" id="%s"', str_replace('.', '-', $name)), $svg);

        file_put_contents(sprintf('%s/templates/%s/doc.svg.twig', $this->projectDir, $shortName), $svg);

        return 0;
    }
}
