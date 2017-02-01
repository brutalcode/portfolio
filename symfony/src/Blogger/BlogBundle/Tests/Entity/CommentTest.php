<?php 
namespace Blogger\BlogBundle\Tests\Entity;

use Blogger\BlogBundle\Entity\Comment;

class CommentTest extends \PHPUnit_Framework_TestCase
{
	public function testSetUser()
	{
		$comment = new Comment();
		$comment->setUser('Boriar');
		$this->assertEquals('Boriar', $comment->getUser());
	}
}
?>