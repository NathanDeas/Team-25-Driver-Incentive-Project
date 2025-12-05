<?php
  $servername = "team25-rds.cobd8enwsupz.us-east-1.rds.amazonaws.com";
  $username = "admin";
  $password = "performancepineapple25";
  $dbname = "team_25_database";

  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check database connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $sql = "SELECT * FROM users WHERE username = '".$_SESSION['User']."'";
  $result = $conn->query($sql);
  $user = $result->fetch_assoc();
  $userID = $user['userID'];
  // echo $userID;

  $sql = "SELECT * FROM user_org WHERE userID = '".$userID."'";
  $result = $conn->query($sql);
  while ($temp = $result->fetch_assoc()) {
    // echo var_dump($temp);
    $userOrgs[] = $temp['orgID'];
    $orgID = $temp['orgID'];
    $points = $temp['point_total'];
  }

  $userOrgs = "('".implode("', '", $userOrgs)."')";

  $sql = "SELECT * FROM organizations WHERE orgID IN $userOrgs";
  $userOrgNames = array();
  $result = $conn->query($sql);
  while ($temp = $result->fetch_assoc()) {
    $userOrgNames[] = $temp['org_name'];
  }

  $sql = "SELECT * FROM user_org WHERE userID = '$userID' and active_cat = 1";
  $result = $conn->query($sql);
  $activeOrg = $result->fetch_assoc()['orgID'];

  $orgCats = array();
  $sql = "SELECT * FROM org_catalogs WHERE org_id IN $userOrgs AND active = 1";
  $result = $conn->query($sql);
  while ($temp = $result->fetch_assoc()) {
    $orgID = $temp['org_id'];
    $sql = "SELECT * FROM organizations WHERE orgID = $orgID;";
    $orgResult = $conn->query($sql);
    $org = $orgResult->fetch_assoc();
    $temp['org_name'] = $org['org_name'];
    $orgCats[] = $temp;
    if ($temp['org_id'] == $activeOrg) {
      $catalog = $temp;
    }
  }
?>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="css/catalog.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Catalog</title>
  <style>

  </style>
</head>
<body>  
  <!-- <input type="submit" name="viewCart" value="cart" class="cartButton"> -->
  <?php
  // if(isset($_POST['viewCart']) || isset($_POST['purchaseCart']))
  // {
  //   include('catCart/cart.php');
  // }
  // else{
  ?>
  <div class="body-container">
    <ul id="cart-list">
      <span id="cart-titles">
        <h2><?php echo $_SESSION['User']?>'s Cart</h2>
        <h2>Points: <?php echo $points ?></h2>
      </span>
      <ul id="cart-items">
        <p id="empty-cart">Cart is empty!</p>
      </ul>
      <div id="checkout-div">
        <span id="total-label" value="0">Total: 0 points</span>
        <button name="checkout" id="checkout">Checkout</button>
      </div>
    </ul>
    <br>
    <div class="grid">
      <i class="fas fa-shopping-cart"></i>
      <select name="catalog-select" id="catalog-select">
        <option value="0">Select Catalog</option>
        <?php
          $i = 0;
          foreach ($orgCats as $cat) {
            echo '<option value="'.$cat['org_id'].'">'.$cat['cat_name'].' ('.$cat['org_name'].')</option>';
            $i++;
          }
        ?>
      </select>
    </div>
    <h1 id="header"><?php echo $catalog['cat_name'] ?></h1>
    <h2 id="sub-header">(<?php echo $catalog['org_name']?>)</h2>
    <span>
      <form id=search-form>
        <label for="search-input">Search:</label>
        <input type="text" name="search-input" id="search-input">
        <button type="submit"><i class="fas fa-search"></i></button>
      </form>
    </span>
      <h2 id="subheader">iTunes Most Popular</h2>
      <select name="sort-by" id="sort-by-dropdown">
        <option value="1">Populartity</option>
        <option value="2">Points (↓)</option>
        <option value="3">Points (↑)</i></option>
      </select>
      <div class="all-songs">
    </div>
  </div>
</body>
</html>
  
<script>
  $(document).ready(function() {
    function handlePlay(){
      const icon = $(this).children()[0]
      const audio = $(icon).children()[0]
      audio.volume = .15
      if ($(icon).hasClass('fa-play')) {
        const playing = $('.fa-pause').children()
        if (playing.length) {
          $('.fa-pause').css('margin-left', '5px')
          $('.fa-pause').attr('class', 'fas fa-play')
          playing[0].pause()
        }
        $(icon).attr('class', 'fas fa-pause')
        $(icon).css('margin-left', 0)
        // console.log('playing')
        audio.play()
      } else {
        $(icon).attr('class', 'fas fa-play')
        $(icon).css('margin-left', '5px')
        audio.pause()
        // console.log('paused')
      }
    };
    $('#checkout-div').hide();

    $('#checkout').on('click', function() {
      const total = $('#total-label').attr('value')
      if (total > <?php echo $points?>) {
        alert('You do not have enough points to purchase these songs!')
        return;
      }
      // $(this).prop('disabled', true)
      // e.preventDefault()
      // $.post({
      //   url: "purchase.php",
      //   data: {points: $(this).attr('data-song-price'), name: $(this).attr('data-song-name')},
      //   success: function(response){
      //     console.log(response);
      //   }
      // })
    });

    $('#catalog-select').on('change', function() {
      const orgID = $(this).val()
      if (orgID == 0) return;
      $.post({
        url: 'catalogHelpers/changeCatalog.php',
        data: {orgID: orgID, userID: <?php echo $userID ?>},
        success: function(response) {
          console.log(response)
          location.reload()
        }
      })
    })

    $('#cart-items').on('click', '#rm-cart-item', function() {
      $('#total-label').attr('value', parseInt($('#total-label').attr('value')) - $(this).attr('points'));
      $('#total-label').html(`Total: ${$('#total-label').attr('value')} points`);
      $('[data-song-name="'+$(this).parent().find('.song-info').find('#cart-song-title').attr('value')+'"]').html('Add to Cart');
      $('[data-song-name="'+$(this).parent().find('.song-info').find('#cart-song-title').attr('value')+'"]').prop('disabled', false);
      $(this).parent().remove();
      if ($('#total-label').attr('value') == 0) {
        $('#checkout-div').hide();
        $('#empty-cart').show();
      }
    })

    $('#sort-by-dropdown').on('change', function() {
      const sortBy = $(this).val()
      const songs = $('.all-songs').children()
      if (sortBy == 1) {
        // sort by id of container
        songs.sort(function(a, b) {
          return $(a).attr('id') - $(b).attr('id')
        })
      } else if (sortBy == 2) {
        songs.sort(function(a, b) {
          return parseInt($(a).find('.points').html()) - parseInt($(b).find('.points').html())
        })
      } else if (sortBy == 3) {
        songs.sort(function(a, b) {
          return parseInt($(b).find('.points').html()) - parseInt($(a).find('.points').html())
        })
      }
      $('.all-songs').html(songs)
    })


    let idArr = [];
    let songData = [];
    let cart = [];
    const pointFactor = 1;
    const pointConversion = 100 / pointFactor;

    const url = 
    "<?php
      echo 'https://corsproxy.io/?https://rss.applemarketingtools.com/api/v2/'.$catalog['country'].'/music/most-played/100/'.$catalog['sf_mt'].'.json'
    ?>"

    getIds = (data) => {
      let ids = []
      for (let i=0; i< data.length; i++) {
        ids[i] = (data[i]['id']);
      }
      return ids;
    }

    async function getData(getUrl) {
      let temp = [];
      await $.get({
        url: getUrl,
        headers: {
          'origin': 'corsproxy.io'
        },
        success: function(data) {
          temp = data['feed']['results']
        }
      })
      return temp
    }

    function makeIdString(arr) {
      tempArr = []
      for (song in arr) {
        tempArr[song] = arr[song]['id']
      }
      return tempArr.join()
    }

    async function request2(urls) {
      retults = []
      await $.get({
        url: 'https://corsproxy.io/?https://itunes.apple.com/lookup?id='+urls,
        headers: {
          'origin': 'corsproxy.io'
        },
        dataType: 'json',
        success: function(data) {
          // console.log(data['results'])
          results = data['results']
        }
      })
      return results
    }

    const cartIcon = $('.grid>i')
    cartIcon.on('click', function() {
      if (cartIcon.attr('class') == 'fas fa-shopping-cart') {
        cartIcon.attr('class', 'fas fa-times');
        $('#cart-list').css('right', 'calc(-1.5rem - 3px)');
      } else {
        cartIcon.attr('class', 'fas fa-shopping-cart');
        $('#cart-list').css('right', '-100%');
      }
    })


    getData(url).then((chartData) => {
      // console.log(chartData)
      const idString = makeIdString(chartData)
      // console.log(idString)
      
      request2(idString).then((parsedData) => {

        // console.log(parsedData)
        for (let i=0; i< parsedData.length; i++) {
          curJSON = parsedData[i];
          let explicit = parsedData[i]['contentAdvisoryRating'];
          (explicit == 'Explicit') ? explicit = '(E)': explicit = ''
          const art = parsedData[i]['artworkUrl100'].slice(0, -13)+'800x800.jpg';
          const name = parsedData[i]['trackName'];
          const artist = parsedData[i]['artistName'];
          const preview = parsedData[i]['previewUrl'];
          const points = parseInt(parsedData[i]['trackPrice']*pointConversion);
          if (points <= 0) {
            continue;
          }
          const remExplicit = '<?php echo $catalog['sf_re']?>'
          if (remExplicit == 'yes' && explicit == '(E)') {
            console.log('explicit');
            continue;
          }
          $('.all-songs').append(/*html*/`
            <div class="song-container" id="${i}">
              <div class="art-container">
                <div class="point-container">
                  <p name = "points" class="points">${points}</p>
                </div>
                <div class="play-container">
                  <i class="fas fa-play" id="play-icon" style="margin-left: 5px">
                    <audio src="${preview}">
                  </i>
                </div>
                <img src="${art}">
              </div>
              <div class="song-title-container">
                <p id="song-title">${name} - ${artist} ${explicit}</p>
                <button name = "name" class="add-to-cart" data-song-price="${points}" data-song-name="${name}">Add to Cart</button>
              </div>
            </div>
          `);
        }
        $('.add-to-cart').on('click', function() {
          const cartItems = $('#cart-items');
          if ($('#empty-cart').length) {
            $('#empty-cart').hide();
            $('#checkout-div').show();
          }

          const songContainer = $(this).parent().parent()
          const songBtn = $(this)
          songBtn.html('<i class="fas fa-check"></i>')
          songBtn.prop('disabled', true)
          const songName = songBtn.attr('data-song-name')
          const songPrice = parseInt(songBtn.attr('data-song-price'))
          $('#total-label').attr('value', parseInt($('#total-label').attr('value')) + songPrice)
          $('#total-label').html(`Total: ${$('#total-label').attr('value')} points`)
          const cartEntry = cartItems.append(/*html*/`
            <li>
              <img src="${songContainer.children().find('img').attr('src')}" alt="${songName}">
              <div class="song-info">
                <p id="cart-song-title" value="${songName}">${songName}</p>
                <p value="129">Points: ${songPrice}</p>
              </div>
              <div></div>
              <i class="fas fa-times" id="rm-cart-item" points="${songPrice}"></i>
            </li>
          `)
        })

        $('#search-form').on('submit', function(e) {
          e.preventDefault();
          console.log('searching')
          const search = $('#search-input').val();
          const data = {
            term: search,
            country: '<?php echo $catalog['country']?>',
            media: '<?php echo $catalog['se_mt']?>',
            entity: 'song',
            limit: 100
          }
          $.post({
            url: 'https://corsproxy.io/?https://itunes.apple.com/search',
            data: data,
            headers: {
              'origin': 'corsproxy.io'
            },
            success: function(data) {
              return data
            }
          }).then((data) => {
            const parsedData = JSON.parse(data)['results']
            $('.all-songs').html('')
            for (let i = 0; i < parsedData.length; i++) {
              curJSON = parsedData[i];
              let explicit = parsedData[i]['contentAdvisoryRating'];
              (explicit == 'Explicit') ? explicit = '(E)': explicit = ''
              const art = parsedData[i]['artworkUrl100'].slice(0, -13)+'800x800.jpg';
              const name = parsedData[i]['trackName'];
              const artist = parsedData[i]['artistName'];
              const preview = parsedData[i]['previewUrl'];
              const points = parseInt(parsedData[i]['trackPrice']*pointConversion);
              if (points <= 0) {
                continue;
              }
              const remExplicit = '<?php echo $catalog['se_re']?>'
              if (remExplicit == 'yes' && explicit == '(E)') {
                console.log('explicit');
                continue;
              }
              $('.all-songs').append(/*html*/`
                <div class="song-container" id="${i}">
                  <div class="art-container">
                    <div class="point-container">
                      <p name = "points" class="points">${points}</p>
                    </div>
                    <div class="play-container">
                      <i class="fas fa-play" id="play-icon" style="margin-left: 5px">
                        <audio src="${preview}">
                      </i>
                    </div>
                    <img src="${art}">
                  </div>
                  <div class="song-title-container">
                    <p id="song-title">${name} - ${artist} ${explicit}</p>
                    <button name = "name" class="add-to-cart" data-song-price="${points}" data-song-name="${name}">Add to Cart</button>
                  </div>
                </div>
              `);
            }
        })

        // $('.play-container').on('click', function() {
        //   const icon = $(this).children()[0]
        //   const audio = $(icon).children()[0]
        //   audio.volume = .15
        //   // console.log(audio)
        //   if ($(icon).hasClass('fa-play')) {
        //     const playing = $('.fa-pause').children()
        //     if (playing.length) {
        //       $('.fa-pause').css('margin-left', '5px')
        //       $('.fa-pause').attr('class', 'fas fa-play')
        //       playing[0].pause()
        //     }
        //     $(icon).attr('class', 'fas fa-pause')
        //     $(icon).css('margin-left', 0)
        //     audio.play()
        //   } else {
        //     $(icon).attr('class', 'fas fa-play')
        //     $(icon).css('margin-left', '5px')
        //     audio.pause()
        //   }
        // });
      })
    })
    $(document).on('click', '.play-container', handlePlay)
    })
  })
</script>