<?php

/**
 * @group API
 * @group Database
 * @group medium
 * @covers ApiQueryRevisions
 */
class ApiQueryRevisionsTest extends ApiTestCase {

	/**
	 * @group medium
	 */
	public function testContentComesWithContentModelAndFormat() {
		$pageName = 'Help:' . __METHOD__;
		$title = Title::newFromText( $pageName );
		$page = WikiPage::factory( $title );

		$page->doEditContent(
			ContentHandler::makeContent( 'Some text', $page->getTitle() ),
			'inserting content'
		);

		$apiResult = $this->doApiRequest( [
			'action' => 'query',
			'prop' => 'revisions',
			'titles' => $pageName,
			'rvprop' => 'content',
		] );
		$this->assertArrayHasKey( 'query', $apiResult[0] );
		$this->assertArrayHasKey( 'pages', $apiResult[0]['query'] );
		foreach ( $apiResult[0]['query']['pages'] as $page ) {
			$this->assertArrayHasKey( 'revisions', $page );
			foreach ( $page['revisions'] as $revision ) {
				$this->assertArrayHasKey( 'contentformat', $revision,
					'contentformat should be included when asking content so client knows how to interpret it'
				);
				$this->assertArrayHasKey( 'contentmodel', $revision,
					'contentmodel should be included when asking content so client knows how to interpret it'
				);
			}
		}
	}
}
