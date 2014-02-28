<?php
App::uses('CakeEmail', 'Network/Email');

class TrapEmail extends CakeEmail {
	/**
	 * Real recipients
	 *
	 * List of email's that realy receive the email.
	 *
	 * @var array
	 */
	public $realRecipients = array();

	public function __construct($config = null) {
		if (!empty($config['realRecipients'])) {
			$this->realRecipients = $config['realRecipients'];
		}

		return parent::__construct($config);
	}

	/**
	 * Override CakeEmail method to backup original recipients in header
	 *  and replace them by address spec in $config['realRecipients']
	 *
	 * @param string|array $content String with message or array with messages
	 * @return array
	 */
	public function send($content = null) {
		if ($this->realRecipients) {
			$originalTo = $this->to();
			$originalCC = $this->cc();
			$originalBCC = $this->bcc();
			$this->to($this->realRecipients);
			if ($originalTo) {
				$this->addHeaders(array('X-intended-to' => join(', ', $originalTo)));
			}
			if ($originalCC) {
				$this->addHeaders(array('X-intended-cc' => join(', ', $originalCC)));
			}
			if ($originalBCC) {
				$this->addHeaders(array('X-intended-bcc' => join(', ', $originalBCC)));
			}
		}

		return parent::send($content);
	}
}
