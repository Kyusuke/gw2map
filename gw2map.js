var map = L.map('map', { maxBounds: [ [-79.0, -180.0], [79.0, 180.0] ] }).setView([0, 0], 2);
        L.tileLayer('GW2/{z}/{x}/{y}.jpg', {
            minZoom: 1,
            maxZoom: 6,
            attribution: 'Stuff by Kyu',
            tms: true,
			noWrap: true
        }).addTo(map);