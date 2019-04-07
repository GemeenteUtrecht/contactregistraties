<?php
// api/src/Controller/CreateBookPublication.php

namespace App\Controller;

use App\Entity\Issue;

class IssueController
{
	public function __invoke(Issue $data): Issue
	{
		//$this->bookPublishingHandler->handle($data);
		
		return $data;
	}
}