<?php

namespace Saikat;

use ComposerScriptEvent;


class PostInstallEventHandler
{
	public static function copyConfig()
	{
		echo "called";
	}
}