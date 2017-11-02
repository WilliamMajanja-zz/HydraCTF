<?hh // strict

require_once ($_SERVER['DOCUMENT_ROOT'].'/../vendor/autoload.php');

/* HH_IGNORE_ERROR[1002] */
SessionUtils::sessionStart();
SessionUtils::enforceLogin();

class ConfigurationController extends DataController {
  // Refresh rate for teams/leaderboard in milliseconds
  private string $teams_cycle = "5000";
  // Refresh rate for map/announcements in milliseconds
  private string $map_cycle = "5000";
  // Refresh rate for configuration values in milliseconds
  private string $conf_cycle = "10000";
  // Refresh rate for commands in milliseconds
  private string $cmd_cycle = "10000";

  public async function genGenerateData(): Awaitable<void> {
    $conf_data = (object) array();

    $control = new Control();

    $gameboard = await Configuration::gen('gameboard');

    /* HH_FIXME[1002] */
    /* HH_FIXME[2011] */
    $conf_data->{'currentTeam'} = SessionUtils::sessionTeamName();
    $conf_data->{'gameboard'} = $gameboard->getValue();
    $conf_data->{'refreshTeams'} = $this->teams_cycle;
    $conf_data->{'refreshMap'} = $this->map_cycle;
    $conf_data->{'refreshConf'} = $this->conf_cycle;
    $conf_data->{'refreshCmd'} = $this->cmd_cycle;
    $conf_data->{'progressiveCount'} = await Progressive::genCount();

    $this->jsonSend($conf_data);
  }
}

$confController = new ConfigurationController();
\HH\Asio\join($confController->genGenerateData());
