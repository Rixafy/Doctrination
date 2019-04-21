<?php

declare(strict_types=1);

namespace Rixafy\Language\Command;

use Doctrine\ORM\EntityManagerInterface;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Rixafy\Language\Exception\LanguageNotFoundException;
use Rixafy\Language\LanguageData;
use Rixafy\Language\LanguageFacade;
use Rixafy\Language\LanguageFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class LanguageUpdateCommand extends Command
{
	/** @var LanguageFactory */
	private $languageFactory;

	/** @var LanguageFacade */
	private $languageFacade;

	/** @var EntityManagerInterface */
	private $entityManager;

	public function __construct(
		LanguageFactory $languageFactory,
		LanguageFacade $languageFacade,
		EntityManagerInterface $entityManager
	) {
		$this->languageFactory = $languageFactory;
		$this->languageFacade = $languageFacade;
		$this->entityManager = $entityManager;
		parent::__construct();
	}

	public function configure(): void
	{
		$this->setName('rixafy:language:update');
		$this->setDescription('Updates language database from online json data.');
	}

	public function execute(InputInterface $input, OutputInterface $output): void
	{
		$payload = @file_get_contents('https://gist.githubusercontent.com/piraveen/fafd0d984b2236e809d03a0e306c8a4d/raw/eb8020ec3e50e40d1dbd7005eb6ae68fc24537bf/languages.json');

		if (!$payload) {
			$output->writeln('<fg=red;options=bold>Json feed is unreachable</>');
		} else {
			try {
				$updated = 0;
				$created = 0;

				$json = Json::decode($payload);

				foreach ($json as $iso => $value) {
					$language = null;

					try {
						$language = $this->languageFacade->getByIso($iso);

						$languageData = $language->getData();
						$languageData->name = $value->name;
						$languageData->nameOriginal = $value->nativeName;

						$language->edit($languageData);

					} catch (LanguageNotFoundException $e) {
						$languageData = new LanguageData();
						$languageData->iso = $iso;
						$languageData->name = $value->name;
						$languageData->nameOriginal = $value->nativeName;

						$language = $this->languageFactory->create($languageData);

						$this->entityManager->persist($language);
					}
				}

				$this->entityManager->flush();

				$output->writeln('<fg=green;options=bold></>');
				$output->writeln('<fg=green;options=bold>Languages updated: ' . $updated . '</>');
				$output->writeln('<fg=green;options=bold>Languages created: ' . $created . '</>');
				$output->writeln('<fg=green;options=bold></>');

			} catch (JsonException $e) {
				$output->writeln('<fg=red;options=bold>Language json is invalid</>');
			}
		}
	}
}