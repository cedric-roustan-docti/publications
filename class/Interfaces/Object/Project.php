<?php

namespace Interfaces\Object;

use Interfaces\Object;

/**
 * Class to manage a project
 */
interface Project extends Object {
	/**
	 * Get project object unique id
	 *
	 * @return int
	 */
	public function getId();

	/**
	 * Get project name
	 *
	 * @return string
	 */
	public function getName();

	/**
	 * Get project description
	 *
	 * @return string
	 */
	public function getDescription();

	/**
	 * Get base of repository path (to detect merge from other branches of same repository)
	 *
	 * @return string
	 */
	public function getVcsBase();

	/**
	 * Get path of branch after $vcs_base
	 *
	 * @return string
	 */
	public function getVcsPath();

	/**
	 * Get full VCS repository url
	 *
	 * @return string
	 */
	public function getVcsRepository();

	/**
	 * Get Bug Tracker id to filter into query
	 *
	 * @return string
	 */
	public function getBugTrackerId();

	/**
	 * Return true if this project is displayable as complete project
	 *
	 * @return bool
	 */
	public function isVisible();

	/**
	 * Return true if this project can have publications
	 *
	 * @return bool
	 */
	public function hasProd();

	/**
	 * Return project custom mail content template
	 *
	 * @return string
	 */
	public function getMailContent();

	/**
	 * Return project custom mail subject template
	 *
	 * @return string
	 */
	public function getMailSubject();

	/**
	 * Return project custom mail content template in case of post publication
	 *
	 * @return string
	 */
	public function getMailPostPubliContent();

	/**
	 * Return project custom mail subject template in case of post publication
	 *
	 * @return string
	 */
	public function getMailPostPubliSubject();

	/**
	 * Return mail content template to use for this project
	 *
	 * @return string
	 */
	public function getDisplayMailContent();

	/**
	 * Return mail subject template to use for this project
	 *
	 * @return string
	 */
	public function getDisplayMailSubject();

	/**
	 * Return mail content template to use for this project in case of post publication
	 *
	 * @return string
	 */
	public function getDisplayMailPostPubliContent();

	/**
	 * Return mail subject template to use for this project in case of post publication
	 *
	 * @return string
	 */
	public function getDisplayMailPostPubliSubject();

	/**
	 * Set project name
	 *
	 * @param string $name
	 */
	public function setName($name);

	/**
	 * Set project description
	 *
	 * @param string $description
	 */
	public function setDescription($description);

	/**
	 * Set base of repository path (to detect merge from other branches of same repository)
	 *
	 * @param string $vcs_base
	 */
	public function setVcsBase($vcs_base);

	/**
	 * Set path of branch after $vcs_base
	 *
	 * @param string $vcs_path
	 */
	public function setVcsPath($vcs_path);

	/**
	 * Set Bug Tracker id to filter into query
	 *
	 * @param string $bug_tracker_id
	 */
	public function setBugTrackerId($bug_tracker_id);

	/**
	 * Set if project is accessible
	 *
	 * @param bool $visible
	 */
	public function setVisible($visible);

	/**
	 * Set if this project can create Publication objects
	 *
	 * @param bool $has_prod
	 */
	public function setHasProd($has_prod);

	/**
	 * Set project custom mail content template
	 *
	 * @param string $mail_content
	 */
	public function setMailContent($mail_content);

	/**
	 * Set project custom mail subject template
	 *
	 * @param string $mail_subject
	 */
	public function setMailSubject($mail_subject);

	/**
	 * Set project custom mail content template in case of post publication
	 *
	 * @param string $mail_post_publi_content
	 */
	public function setMailPostPubliContent($mail_post_publi_content);

	/**
	 * Set project custom mail subject template
	 *
	 * @param string $mail_post_publi_subject in case of post publication
	 */
	public function setMailPostPubliSubject($mail_post_publi_subject);

	/**
	 * Add project defined as external for this project
	 *
	 * @param Project $project
	 */
	public function addExternal(Project $project);

	/**
	 * Remove project defined as external for this project
	 *
	 * @param Project $project
	 */
	public function removeExternal(Project $project);

	/**
	 * Get all external projects
	 *
	 * @return Project[]
	 */
	public function getExternals();

	/**
	 * Add a recipient for this project only
	 *
	 * @param string $email Email an be formated like : "John Doe <john.doe@email.com>"
	 */
	public function addRecipient($email);

	/**
	 * Remove a recipient for this project only
	 *
	 * @param string $email email must be only email address or exactly same parameter done into addRecipient
	 */
	public function removeRecipient($email);

	/**
	 * Get list of Email recipients for publication notification for this project
	 *
	 * @return string[]
	 */
	public function getRecipients();

	/**
	 * Get project url
	 *
	 * @return string
	 */
	public function getUrl();

	/**
	 * Get project url without pagination
	 *
	 * @return string
	 */
	public function getUrlSeeAll();

	/**
	 * Get project url for one page without header
	 *
	 * @return string
	 */
	public function getUrlSeeMore();

	/**
	 * Get project url to add publication
	 *
	 * @return string
	 */
	public function getUrlAddPublication();
}
