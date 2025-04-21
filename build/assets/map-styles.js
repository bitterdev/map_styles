window.mapStylerGoogleMapInit = function () {
    $(".googleMapCanvas").each((function () {
        try {
            var e = $(this).data("latitude"), t = $(this).data("longitude"), a = $(this).data("zoom"),
                o = $(this).data("scrollwheel"), r = $(this).data("draggable"),
                n = new google.maps.LatLng(e, t), s = {
                    zoom: a,
                    center: n,
                    mapTypeId: google.maps.MapTypeId.ROADMAP,
                    streetViewControl: !1,
                    scrollwheel: o,
                    draggable: r,
                    styles: CCM_MAP_STYLES || [],
                    mapTypeControl: !1
                }, p = new google.maps.Map(this, s);
            new google.maps.Marker({position: n, map: p})
        } catch (e) {
            $(this).replaceWith($("<p />").text(e.message))
        }
    }))
};