<?php if(defined('SKL') && $user){?>
	<span class="skill">
		<?=(!is_writable(__DIR__) ? "<span class='dir'>Current directory is not writable, some features may be unavailable!</span> | " : "")?>
		powered by <a target="_blank" href="//antichat.com/threads/456126/">SkillProgrammer</a>
	</span>
<?php } ?>
