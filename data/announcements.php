<?hh // strict

require_once ($_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php');

/* HH_IGNORE_ERROR[1002] */
SessionUtils::sessionStart();
SessionUtils::enforceLogin();

class AnnouncementsDataController extends DataController {
  public async function genGenerateData(): Awaitable<void> {
    $data = array();

    $all_announcements = await Announcement::genAllAnnouncements();
    foreach ($all_announcements as $announcement) {
      array_push($data, $announcement->getAnnouncement());
    }

    $this->jsonSend($data);
  }
}

$announcementsData = new AnnouncementsDataController();
\HH\Asio\join($announcementsData->genGenerateData());
