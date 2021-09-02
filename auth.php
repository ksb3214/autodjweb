   document.getElementById('authorize').setAttribute('href', 'https://id.twitch.tv/oauth2/authorize?client_id=' + client_id + '&redirect_uri=' + encodeURIComponent(redirect) + '&response_type=token')

        if (document.location.hash) {
            var parsedHash = new URLSearchParams(window.location.hash.substr(1));
            if (parsedHash.get('access_token')) {
                access_token = parsedHash.get('access_token');

                document.getElementById('loading').textContent = 'Loading Profile';

                fetch(
                    'https://api.twitch.tv/helix/users',
                    {
                        "headers": {
                            "Client-ID": client_id,
                            "Authorization": "Bearer " + access_token
                        }
                    }
                )
