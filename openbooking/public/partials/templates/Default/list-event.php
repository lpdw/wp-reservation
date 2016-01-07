<?php
$html[] = '<div class="event_list_header">';
if(isset($_SESSION['id']))
{
$html[] = '<p> '.$_SESSION['first_name'].' '.$_SESSION['last_name'].' <button class="event_log_out"> Se Déconnecter </button> </p>';
}
$html[] = '</div>';

$html[] = '<ul class="event_list_content">';
foreach ($events as $event)
{
  $html[] = '<li class="event_list_element">';

  $html[] = '<div class="element_header">';
  $html[] = '<h4 class="event_name">'.$event['name'].'</h4>';
  $html[] = '<p class="event_information"> Organizer: <a href="mailto:'.$event['organizer_email'].'"> '.$event['organizer'].' </a> <br/>
  Location: '.$event['localisation_name'].'<br/>
  Date: '.$event['date'].'</p>';
  $html[] = '</div>';

  $html[] = '<div class="element_content">';
  $html[] = '<p class="event_description">'.$event['description'].'</p>';
  $html[] = "<script>
  var map_list_event_".$event['id'].";
  function initialize_map_list_element_".$event['id']."() {
    map_list_event_".$event['id']." = new google.maps.Map(document.getElementById('event_map_list_element_".$event['id']."'), {
      center: {lat: 46.2276380, lng: 2.2137490},
      zoom: 2
    });

    // Search for our location name.
    var request_".$event['id']." = {
      query: '".$event['localisation_name']."'
    };


    var service = new google.maps.places.PlacesService(map_list_event_".$event['id'].");
    service.textSearch(request_".$event['id'].", callback_list_event_".$event['id'].");

  }

  // Checks that the PlacesServiceStatus is OK, and adds a marker
  // using the place ID and location from the PlacesService.
  function callback_list_event_".$event['id']."(results, status) {
    // We check if Google know the place
    if (status == google.maps.places.PlacesServiceStatus.OK) {
      // If a place is found
      map_list_event_".$event['id'].".setCenter(results[0].geometry.location);
      map_list_event_".$event['id'].".setZoom(18);
      var marker = new google.maps.Marker({
        map: map_list_event_".$event['id'].",
        place: {
          placeId: results[0].place_id,
          location: results[0].geometry.location
        }
      });
    } else {
      // if not we check for a physical address
      var geocoder = new google.maps.Geocoder();
      geocodeAddress(geocoder, map_list_event_".$event['id'].", '{lat:".$event['localisation_lat'].", lng:".$event['localisation_lng']."}');
    }
  }

  google.maps.event.addDomListener(window, 'load', initialize_map_list_element_".$event['id'].");</script>";
  $html[] = '<div id="event_map_list_element_'.$event['id'].'" style="height:200px;"></div>';
  $html[] = '</div>';

  $html[] = '<div class="element_footer">';
  if($event['open_to_registration'] && !$event['cancelled'])
  {
    if(isset($_SESSION['id'])&&!$event['cancelled']&&$event['open_to_registration'])
    {
      if($event['participating_to_this_event']){
        $html[] = '<p>You are participating to this event.</p>';
        $html[] = '<div class="more">';
        $html[] = '<p> Are you sure you wanna leave this event ? </p>';
        $html[] = '<button class="btn_cancel"> No </button>';
        $html[] = '<button class="event_leave"> Yes </button>';
        $html[] = '</div>';
        $html[] = '<button class="btn_change"> Leave </button>';
      } else {
        if($event['participants_registred'] < $event['participants_max'])
        {
        $html[] = '<button id="event_join"> Join </button>';
        } else {
          $html[] = '<h3> All seats are taken. </h3>';
          $html[] = '<button class="event_alert"> Be alerted if someone leaves.</button>';
        }
      }
    } else {
      $html[] = '<div class="sign-in">';
      $html[] = '<div class="more">';
      $html[] = '<form onsubmit="return false;">';
      $html[] = '<div class="event_server_message"></div>';

      $html[] = '<label for="event_list_first_name_'.$event['id'].'"> Firstname: </label>';
      $html[] = '<input id="event_list_first_name_'.$event['id'].'" class="first_name" name="first_name" type="text" placeholder="Firstname"/>';

      $html[] = '<label for="event_list_last_name_'.$event['id'].'"> Lastname: </label> ';
      $html[] = '<input id="event_list_last_name_'.$event['id'].'" class="last_name" name="last_name" type="text" placeholder="Lastname"/>';

      $html[] = '<label for="event_list_email_'.$event['id'].'"> Email: </label>';
      $html[] = '<input id="event_list_email_'.$event['id'].'" class="email" name="email" type="email" placeholder="Email"/>';

      $html[] = '<label for="event_list_password1_'.$event['id'].'"> Password: </label>';
      $html[] = '<input id="event_list_password1_'.$event['id'].'" class="password1" name="password1" type="password" placeholder="Password"/>';

      $html[] = '<label for="event_list_password2_'.$event['id'].'"> Your password again: </label>';
      $html[] = '<input id="event_list_password2_'.$event['id'].'" class="password2" name="password2" type="password" placeholder="Password"/>';

      $html[] = '<button class="btn_cancel"> Close </button>';
      $html[] = '<button class="event_sign_in"> Sign in </button>';
      $html[] = '</form>';
      $html[] = '</div>';
      $html[] = '<button class="btn_change"> Sign in </button>';
      $html[] = '</div>';

      $html[] = '<div class="log-in">';
      $html[] = '<div class="more">';
      $html[] = '<form onsubmit="return false;">';
      $html[] = '<div class="event_server_message"></div>';

      $html[] = '<label for="event_list_email_log_'.$event['id'].'"> Email: </label>';
      $html[] = '<input id="event_list_email_log_'.$event['id'].'" class="email" name="email_login" type="email" placeholder="Email"/>';

      $html[] = '<label for="event_list_password_'.$event['id'].'"> Password: </label>';
      $html[] = '<input id="event_list_password_'.$event['id'].'" class="password" name="password" type="password" placeholder="password"/>';

      $html[] = '<button class="btn_cancel"> Close </button>';
      $html[] = '<button class="event_log_in"> Log in </button>';

      $html[] = '</form>';
      $html[] = '</div>';
      $html[] = '<button class="btn_change"> Log in </button>';
      $html[] = '</div>';
    }
    $html[] = '<div id="event_server_message"></div>';
  } elseif ($event['cancelled'])
  {
    $html[] = '<p> Event cancelled </p>';
  } elseif (!$event['open_to_registration'])
  {
    $html[] = '<p> Registrations close </p>';
  }
  $html[] = '</div>';
  $html[] = '</li>';
  $html[] = '<hr/>';
}
$html[] = '</ul>';

$html[] = '<div class="event_list_footer">';

$html[] = '</div>';
?>
