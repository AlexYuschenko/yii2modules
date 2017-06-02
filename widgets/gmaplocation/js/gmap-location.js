/**
 * Выбор местоположения - виджет.
 * Виджет запоминает координаты при вводе адреса в инпут и отображает карту Google.
 * Необходимо передавать опции:
 * - address - селектор, для указания адреса;
 * - latitude - селектор для указания широты;
 * - longitude - селектор для указания долготы;
 * - hideMarker - если определено, то не будет установлен маркер на карте при поиске локации;
 * - onLoadMap - если определена функциия, то она будет вызвана при инициализации карты;
 * - addressNotFound - сообщение о не найденном адресе.
 *
 * @param {Object}  options
 * @param {boolean} options.draggable Marker draggable Option
 * TODO: describe other options here
 */
(function($) {
    $.fn.selectLocation = function(options) {
        var self = this;
        var map;
        var geocoder;

        $(document).ready(function() {
            geocoder = new google.maps.Geocoder();
            var mapOptions = {
                center: new google.maps.LatLng(20, 0),
                zoom: options.zoom,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                panControl: true
            };
            map = new google.maps.Map($(self).get(0), mapOptions);

            if (options.onLoadMap) {
                options.onLoadMap(map);
            }

            // маркер найденной точки
            var marker = null;

            /**
             * Создать маркер на карте
             * Передается объект типа google.maps.LatLng
             * @param {Object} latLng
             */
            var createMarker = function(latLng) {
                // удалить маркер если уже был
                if (marker) {
                    marker.remove();
                }
                if (options.hideMarker) {
                    // не нужно устанавливать маркер
                    return;
                }
                marker = new google.maps.Marker({
                    'position'          : latLng,
                    'map'               : map,
                    'draggable'         : options.draggable
                });

                if(options.draggable) {
                    google.maps.event.addListener(marker, 'dragend', function() {
                        marker.changePosition(marker.getPosition());
                    });
                }

                marker.remove = function() {
                    google.maps.event.clearInstanceListeners(this);
                    this.setMap(null);
                };

                marker.changePosition = function(pos) {
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode(
                        {
                            latLng: pos
                        },
                        function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                setLatLngAttributes(results[0].geometry.location);
                                var address_change = [];
                                for (var i = 0; i < results[0].address_components.length; i++) {
                                    if (results[0].address_components[i].types[0] == 'route') {
                                        address_change[0] = results[0].address_components[i]['short_name'];
                                    }
                                    if (results[0].address_components[i].types[0] == 'street_number') {
                                        address_change[1] = results[0].address_components[i]['short_name'];
                                    }
                                }
                                $(options.address).val(address_change.join(', '));
                                $(options.address).data('address', address_change.join(', '));

                                if (typeof options.country != 'undefined') {
                                    for (var i = 0; i < results[0].address_components.length; i++) {
                                        if (results[0].address_components[i].types[0] == 'country') {
                                            if ($(options.country).val() != results[0].address_components[i]['short_name']) {
                                                $(options.address).val('').attr('disabled', 'disabled');
                                                $(options.country).val('');
                                                if (typeof options.region != 'undefined') {
                                                    $(options.region).val('').attr('disabled', 'disabled');
                                                }
                                                if (typeof options.city != 'undefined') {
                                                    $(options.city).val('').attr('disabled', 'disabled');
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            return false;
                        }
                    );
                }
            };

            /**
             * Установить координаты точки
             * @param {Object} point объект типа google.maps.LatLng
             */
            var setLatLngAttributes = function(point) {
                $(options.latitude).val(point.lat());
                $(options.longitude).val(point.lng());
            };

            /**
             * Выбрать местоположение, на входе объект у которго есть geometry
             * @param {Object} item
             */
            var selectLocation = function(item) {
                if (!item.geometry) {
                    return;
                }
                var bounds = item.geometry.viewport ? item.geometry.viewport : item.geometry.bounds;
                var center = null;
                if (bounds) {
                    map.fitBounds(new google.maps.LatLngBounds(bounds.getSouthWest(), bounds.getNorthEast()));
                }
                if (item.geometry.location) {
                    center = item.geometry.location;
                }
                else if (bounds) {
                    var lat = bounds.getSouthWest().lat() + ((bounds.getNorthEast().lat() - bounds.getSouthWest().lat()) / 2);
                    var lng = bounds.getSouthWest().lng() + ((bounds.getNorthEast().lng() - bounds.getSouthWest().lng()) / 2);
                    center = new google.maps.LatLng(lat, lng);
                }
                if (center) {
                    map.setCenter(center);
                    createMarker(center);
                    setLatLngAttributes(center);
                }
            };

            // валидация адреса, если не найдены координаты
            // испльзуется событие из ActiveForm
            if ($(options.address).parents('form').length) {
                var $form = $(options.address).parents('form');
                $form.on('afterValidateAttribute', function(e, attribute, messages) {
                    if (attribute.input == options.address && !$(options.latitude).val() && !$(options.longitude).val() && !messages.length) {
                        // не найдены координаты
                        messages.push(options.addressNotFound);
                        e.preventDefault();
                    }
                });
            }

            $(document).on('blur', options.address, function(){
                if ($(this).val().length && $(this).val() != $(this).data('address')) {
                    $(this).data('address', $(this).val());
                    if (marker) {
                        marker.remove();
                    }
                    var address = $(options.address).val();
                    if (typeof options.city != 'undefined') {
                        if ($(options.city).val().length) {
                            address = address + ', ' + $(options.city).find('option[value="'+$(options.city).val()+'"]').text();
                        }
                    }
                    if (typeof options.region != 'undefined') {
                        if ($(options.region).val().length) {
                            address = address + ', ' + $(options.region).find('option[value="'+$(options.region).val()+'"]').text();
                        }
                    }
                    if (typeof options.country != 'undefined') {
                        if ($(options.country).val().length) {
                            address = address + ', ' + $(options.country).find('option[value="'+$(options.country).val()+'"]').text();
                        }
                    }

                    geocoder.geocode({'address': address}, function(results, status) {
                        if (status == 'OK') {
                            $form.yiiActiveForm('find', options.address.substr(1)).validate = function (attribute, value, messages, deferred, $form) {
                                return true;
                            }
                            selectLocation(results[0]);
                        } else {
                            $form.yiiActiveForm('find', options.address.substr(1)).validate = function (attribute, value, messages, deferred, $form) {
                                messages.push(options.addressNotFound);
                            }
                        }
                        $form.yiiActiveForm('validateAttribute', options.address.substr(1));
                    });
                }
            });

            var defaults = {
                'lat'       : $(options.latitude).val(),
                'lng'       : $(options.longitude).val()
            };
            if (defaults.lat && defaults.lng) {
                var center = new google.maps.LatLng(defaults.lat, defaults.lng);
                map.setCenter(center);
                createMarker(center);
                setLatLngAttributes(center);
            }
        });
    };
})(jQuery);