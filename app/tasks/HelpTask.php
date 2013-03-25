<?php

class HelpTask extends \Phalcon\CLI\Task {

	public function indexAction() {
		$this->writeln('usage: console [task] [action] [params]');
	}

	protected function writeln($text) {
		$f = fopen('php://stdout', 'w');
		fputs($f, $text . PHP_EOL);
		fclose($f);
	}
}
