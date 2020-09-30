<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Changelog\Command;

use Shopware\Core\Framework\Changelog\Processor\ChangelogValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ChangelogCheckCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'changelog:check';

    /**
     * @var ChangelogValidator
     */
    private $validator;

    public function __construct(ChangelogValidator $validator)
    {
        parent::__construct();
        $this->validator = $validator;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Check the validation of a given changelog file. This command will check all files in "changelog/_unreleased" folder, if users don\'t specify a changelog file.')
            ->addArgument('changelog', InputArgument::OPTIONAL, 'The path of changelog file which need to check.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $IOHelper = new SymfonyStyle($input, $output);
        $IOHelper->title('Check the validation of changelog files');

        /** @var string $path */
        $path = $input->getArgument('changelog') ?: '';
        if (!empty($path) && !file_exists($path)) {
            $IOHelper->error('The given file NOT found');

            return 1;
        }

        $output = $this->validator->check($path);
        if (count($output)) {
            foreach ($output as $file => $violations) {
                $IOHelper->writeln((string) $file);
                $IOHelper->writeln(array_map(function ($message) {
                    return '* ' . $message;
                }, $violations));
                $IOHelper->newLine();
            }
            $IOHelper->error('You have some syntax errors in changelog files.');

            return 1;
        }

        $IOHelper->success('Done');

        return 0;
    }
}