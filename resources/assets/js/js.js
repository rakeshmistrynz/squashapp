/**
 * SQUASH_APP Javascript
 * object design source: http://viget.com/inspire/extending-paul-irishs-comprehensive-dom-ready-execution
 *
 * Author: Rakesh Mistry
 * Date: Friday 15 May 2015
 */
SQUASH_APP = {
    common: {
        init: function () {

            // application-wide code

            $('button[type="submit"]').on('click',function(event){
                
                $(this).empty();

                $(this).append('<i class="fa fa-spinner fa-spin"></i><span>&nbsp;Loading</span>')

            })

            //********************************************************************//
            //Ajax Setup
            //********************************************************************//

            /**
             * Ajax setup - required for ajax calls with Laravel 5.
             */
            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                beforeSend: function () {

                    $('.overlay').removeClass('overlay-hide');
                },

                complete: function () {

                    $('.overlay').addClass('overlay-hide');
                }

            });

            //Store mobile menu title, depending on user's chosen location
            if (sessionStorage.getItem('title') && (window.location.pathname != '/bookings/book-a-court')) {

                $('.nav-title').html(sessionStorage.getItem('title'));
            }
            ;

            //Toggle mobile menu
            $('.navbar-toggle').on('click', function () {

                $('.navbar-left').toggleClass('move-left');
                $('body').toggleClass('hide-overflow');

            });

            if(window.location.pathname == '/' || window.location.pathname == '/password/email'|| $.inArray('password',window.location.pathname.split('/')) > -1){

                $('div.container-fluid.full-height').removeClass('full-height');

            }else{

                $('div.container-fluid.biege').addClass('full-height');
            };

            //Mobile menu icon toggle
            $('.dropdown-toggle').on('click', function () {

                var title = $(this).text();

                sessionStorage.setItem('title', title);
            });

            $(document).ready(function(){
                $('body').append('<div id="toTop" class="btn btn-info"><i class="fa fa-arrow-up"></i></div>');
                $(window).scroll(function () {
                    if ($(this).scrollTop() != 0) {
                        $('#toTop').fadeIn();
                    } else {
                        $('#toTop').fadeOut();
                    }
                });
                $('#toTop').click(function(){
                    $("html, body").animate({ scrollTop: 0 }, 600);
                    return false;
                });
            });

        },

        /**
         * Ajax function to get booking data for each court
         * @param  string date    yyyy-mm-dd
         * @return object       An array of timeslots for each court, including any booking data for a booked timeslot for the selected date.
         *
         */
        getdata: function(date) {

            return $.ajax({

                url: 'get_timeslots',
                type: 'post',
                data: date,
                dataType: "json"
            });
        }
    },

    bookings: {
        init: function () {
            // controller-wide code
        },

        show: function () {

            /**
             * Place booking information inside modal to confirm booking to be deleted.
             */
            $('.list-group').on('click', 'a', function () {

                console.log($(this).parent().parent());

                $('input[name="booking_id"]').val($(this).data('bookingid'));

                $('.modal-body').append($(this).parent().parent().html());

                $('.modal-body').find('p').addClass('text-center');

                $('.modal-body a').remove();

                $('#smallModal').modal('show');

            });

            /**
             * Remove any booking information, displayed in modal.
             */
            $('#smallModal').on('hide.bs.modal', function () {

                $('.modal-body h5').nextAll().remove();

            });


        }, //EO bookings.show

        create: function () {

            /**
             * Get string representation of a date.
             */
            function displayDate(date) {

                var thedate = new Date(date);

                var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                return days[thedate.getDay()] + ' ' + thedate.getDate() + ' ' + months[thedate.getMonth()] + ' ' + thedate.getFullYear();
            }

            /**
             * Instantiate Datepicker Plugin object
             */

            var fortnight = new Date(+new Date + 12096e5);

            var datepicker = $('#datepicker').datepicker({

                format: "yyyy-mm-dd",
                todayHighlight: true,
                startDate: "today",
                endDate: fortnight.getFullYear()+'-'+(fortnight.getMonth()+1)+'-'+fortnight.getDate()
            });

            /**
             * Set selected date on Datepicker to the current date on page load
             */
            datepicker.datepicker("setDate", "0");


            $('#datepicker').datepicker().on('changeDate',function(e){

                $('#booking-warn').remove();
            });

            /**
             * Place date in container on page load, so current date is shown on page load
             */
            $('#datevalue').html(
                '<h4 class="text-center">' + displayDate(datepicker.datepicker('getFormattedDate')) + '</h4>'
            );

            /**
             * Variable containing the selected date (current date by default) in date format for database
             * @type string
             */
            var selected_date = datepicker.datepicker('getFormattedDate');

            /**
             * Set the value of the input booking date value to current date as default
             * @type string
             */
            $('input[name="booking_date"]').val(selected_date);


            //********************************************************************//
            //Function/Methods
            //********************************************************************//

            /**
             * Function to render the court timeslots with any available booking data on the page, under each accordion heading
             * @param  object time_slot_obj object returned from ajax call
             * @param  string accordion_id  the id of the accordion where court data will be rendered
             * @return string               HTML rendering the booking data or an available timeslot that can be booked
             */
            function render_time_slot(time_slot_obj, accordion_id) {

                //console.log(time_slot_obj);

                if ((Object.keys(time_slot_obj).length) > 3) {

                    if(time_slot_obj.admin && time_slot_obj.cat_id == 4){

                        return $('#' + accordion_id + ' .accordion-inner').append('<div class="text-center alert alert-danger"><a href="#" class="time_slot" data-time="' + time_slot_obj.time + '" data-court="' + time_slot_obj.court_id + '" data-bookingid="'+time_slot_obj.booking_id+'">'+time_slot_obj.player1+' '+(time_slot_obj.player2?  ' & '+time_slot_obj.player2 : '')+'</br>'+time_slot_obj.booking_description+'</a></div>');
                    }

                    return $('#' + accordion_id + ' .accordion-inner').append('<div class="text-center alert alert-danger">' +time_slot_obj.player1+' '+(time_slot_obj.player2?  ' & '+time_slot_obj.player2 : '')+'</br>'+time_slot_obj.booking_description+'</a></div>');
                }

                return $('#' + accordion_id + ' .accordion-inner').append('<div class="text-center alert alert-success"><a href="#" class="time_slot" data-court="' + time_slot_obj.court_id + '" data-timeslot="' + time_slot_obj.timeslot_id + '">' + time_slot_obj.time + '</a></div>');

            }

            /**
             * Function to append time slots after recieving court booking data from Ajax call
             * @param  object data
             * @param  string accordion_id
             * @return string - HTML
             */
            function append_time_slots(data, accordion_id) {

                $('#' + accordion_id + ' .accordion-inner').html(' ');

                $.each(data, function (key, value) {

                    render_time_slot(data[key], accordion_id);

                });
            }

            /**
             * Function to validate a new booking from Ajax call
             * @param  object data
             * @param  string accordion_id
             * @return string - HTML
             */
            function check_booking(court, date, time) {

                var booking = {
                    'court_id': court,
                    'selected_date': date,
                    'timeslot_id': time
                };

                return $.ajax({

                    url: 'booking_check',
                    type: 'post',
                    data: booking,
                    dataType: "json",
                });

            }


            //********************************************************************//
            //Render Court Times on page load from Ajax Call
            //********************************************************************//

            var post_date = {
                'date': selected_date
            };


            window.SQUASH_APP.common.getdata().always(function () {

            });

            window.SQUASH_APP.common.getdata().fail(function () {

                $('h1').after('<div id="booking-warn" class="row bg-white"><ul class="alert alert-danger text-center"><li class="alert alert-danger">Sorry there is a network error<a href="">Click to refresh page</a></li></ul></div>');
            });

            window.SQUASH_APP.common.getdata(post_date).done(function (data) {

                append_time_slots(data.court_1, 'collapseOne');
                append_time_slots(data.court_2, 'collapseTwo');
                append_time_slots(data.court_3, 'collapseThree');

            });


            //********************************************************************//
            //Events
            //********************************************************************//

            /**
             * Render court data on date selected by user from the date picker
             */
            datepicker.datepicker().on('changeDate', function (e) {

                var selected_date = datepicker.datepicker('getFormattedDate');

                $('input[name="booking_date"]').val(e.format('yyyy-mm-dd'));

                $('#datevalue').html(
                    '<h4 class="text-center">' + displayDate(datepicker.datepicker('getFormattedDate')) + '</h4>'
                );

                var post_date = {
                    'date': selected_date
                };

                window.SQUASH_APP.common.getdata().always(function () {

                });

                window.SQUASH_APP.common.getdata().fail(function () {


                    $('h1').after('<div id="booking-warn" class="row bg-white"><ul class="alert alert-danger text-center"><li class="alert alert-danger">Sorry there is a network error<a href="">Click to refresh page</a></li></ul></div>');

                });

                window.SQUASH_APP.common.getdata(post_date).done(function (data) {

                    append_time_slots(data.court_1, 'collapseOne');
                    append_time_slots(data.court_2, 'collapseTwo');
                    append_time_slots(data.court_3, 'collapseThree');

                });

            });

            /**
             * Event function called when user clicks on available booking slot.
             * Returns an ajax call to double check booking is available and user complies with booking rules - ie is not booking twice etc.
             * Appends timeslot data to booking form in modal if booking is permitted.
             */
            $('.accordion-inner').on('click', 'div.alert-success a', function () {

                $('ul.alert, li.alert').remove();

                var booking_data = $(this).data();

                var time = $(this).html();

                var date = $('#datevalue').html();

                check_booking().always(function () {


                });

                check_booking().fail(function () {


                    $('h1').after('<div id="booking-warn" class="row bg-white"><ul class="alert alert-danger text-center"><li class="alert alert-danger">Sorry there is a network error<a href="">Click to refresh page</a></li></ul></div>');

                });

                check_booking(booking_data.court, datepicker.datepicker('getFormattedDate'), booking_data.timeslot).done(function (data) {


                    if (data.existing_booking) {

                        $('h1').after('<div id="booking-warn" class="row bg-white"><ul class="alert alert-danger text-center"><li class="alert alert-danger">Sorry this booking has been taken by another member. <a href="">Click to refresh page</a></li></ul></div>');

                    //Add Booking error messages here
                    //} else if (data.con_booking) {
                    //
                    //    $('h1').after('<div class="row bg-white"><ul class="alert alert-danger text-center"><li class="alert alert-danger">Sorry cannot make subsequent bookings. Please contact the Club\'s Administrator</li></ul></div>');
                    //
                    //} else if (data.double_booking) {
                    //
                    //    $('h1').after('<div class="row bg-white"><ul class="alert alert-danger text-center"><li class="alert alert-danger">Sorry cannot double book courts. Please contact the Club\'s Administrator</li></ul></div>');

                    } else if (data.user_booking) {

                        $('h1').after('<div id="booking-warn" class="row bg-white"><ul class="alert alert-danger text-center"><li class="alert alert-danger">Sorry you are only limited to one booking per day. Please contact the Club\'s Administrator</li></ul></div>');

                    } else if (data.junior_booking) {

                        $('h1').after('<div id="booking-warn" class="row bg-white"><ul class="alert alert-danger text-center"><li class="alert alert-danger">Sorry Juniors cannot book between 5-7pm. Please contact the Club\'s Administrator</li></ul></div>');

                    } else {

                        $('#time').append(' <span>' + time + '</span>');
                        $('#date').append(' <span>' + displayDate(datepicker.datepicker('getFormattedDate')) + '</span>');
                        $('#court').append(' <span>' + booking_data.court + '</span>');
                        $('input[name="time_slot_id"]').val(booking_data.timeslot);
                        $('input[name="court_id"]').val(booking_data.court);
                        $('#bookingModal').modal('show');

                    }

                });

            });

            /**
             * Remove data from booking form in modal, when user closes the modal.
             */
            $('#bookingModal').on('hide.bs.modal', function (e) {

                $('#time span', this).remove();
                $('#date span', this).remove();
                $('#court span', this).remove();

            });


            //********************************************************************//
            //Search for players
            //********************************************************************//

            /**
             * Initialise the Twitter - Typeahead Typeahead plugin, with the Bloodhound suggestion Engine.
             */
            var engine = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: "./players?name=%QUERY"
            });

            engine.initialize();


            $('.typeahead').typeahead({
                minLength: 3,
                highlight: true
            }, {
                name: 'players',
                displayKey: 'name',
                source: engine.ttAdapter()
            });

            $('.typeahead').on('typeahead:selected', function (e, suggestion, dataset) {

                $('input[name="player2_id"]').val(suggestion.id);

                $('button[type="submit"]').attr('disabled', false);
            });

            //********************************************************************//
            //Front-end Validation
            //********************************************************************//

            /**
             * Hide the playing partner field by default. Default booking category is practice.
             */
            $('#playing_partner').hide();

            /**
             * Enable the booking button by default
             */
            $('button[type="submit"]').attr('disabled', false);

            /**
             * Event to check if booking category is changed, then users enters the other players name, before submiting the booking request.
             */
            $('select[name="booking_cat_id"]').on("change", function () {

                if ($(this).val() == 1) {

                    $('#playing_partner').hide(500);

                    $('input[name="player2_id"]').val(0);

                    $('button[type="submit"]').attr('disabled', false);

                } else {

                    $('#playing_partner').show(500);

                    $('button[type="submit"]').attr('disabled', true);
                }

            });

            /**
             * Place booking information inside modal to confirm booking to be deleted.
             */
            $('.accordion-inner').on('click', 'div.alert-danger a', function () {

                $('input[name="booking_id"]').val($(this).data('bookingid'));

                $('#deleteModal .modal-body').append('<p class="text-center">'+$(this).html()+' @ '+$(this).data('time')+'</p>');

                $('#deleteModal').modal('show');

            });

            /**
             * Remove any booking information, displayed in modal.
             */
            $('#deleteModal').on('hide.bs.modal', function () {

                $('.modal-body h5').nextAll().remove();

            });


            $(window).on('resize load', function() {
                if ($(this).width() < 767) {
                    $('.collapse').removeClass('in');
                    $('.collapse').addClass('out');
                   $('#collapseTwo').addClass('out, in');
                } else {
                    $('.collapse').removeClass('out');
                    $('.collapse').addClass('in');
                }
            });

        } // EO Booking.create
    }, // EO Booking
    ladder: {
        init: function () {
            // application-wide code
        },

        index: function () {

            //********************************************************************//
            //Functions
            //********************************************************************//


            /**
             * Function to get ladder data.
             * @returns json
             */
            function getladder() {

                return $.ajax({

                    url: './get_ladder',
                    type: 'post',
                    dataType: "json"
                });
            };

            var date = $('#datevalue').html();

            getladder().always(function () {


            });

            getladder().fail(function () {


                $('h1').after('<div class="row bg-white"><ul class="alert alert-danger text-center"><li class="alert alert-danger">Sorry there is a network error <a href="">Click to refresh page</a></li></ul></div>');

            });

            getladder().done(function (data) {

                var data = data;

                $.each(data, function (key, value) {

                    $('#the-ladder').append('<li class="list-group-item clearfix" data-player="'+data[key]['dataname']+'"><div><img src="/images/profile-image/'+data[key]['photo']+'" class="img-circle" width="50px"><span class="name">'+data[key]['name']+'</span></div><div><span class="points">'+data[key]['points']+'</span></div></li>');

                });

                $('.points').each(function () {
                    $(this).prop('Counter',0).animate({
                        Counter: $(this).text()
                    }, {
                        duration: 4000,
                        easing: 'swing',
                        step: function (now) {
                            $(this).text(Math.ceil(now));
                        }
                    });
                });

            });//EO getLadder

            /**
             * Click on user on ladder to get player profile.
             */
            $('#the-ladder').on('click', 'li', function () {

                var playerid = $(this).data('player');

                window.location = './player-profile/' + playerid;
            });

        }, // EO Ladder.index

        profile: function () {

            //********************************************************************//
            //Functions
            //********************************************************************//

            var player = window.location.pathname.split("/").pop().split("-");

            var name = {'first_name': player[0], 'last_name': player[1]};

            /**
             * Get Profile Data
             * @returns JSON
             */
            function getprofile() {

                return $.ajax({

                    url: 'get_profile',
                    type: 'post',
                    dataType: "json",
                    data: name
                });
            };


            function animateNumbers(element, countTo) {

                $({countNum: $(element).text()}).animate({countNum: countTo},
                    {
                        duration: 1000,
                        easing: 'linear',
                        step: function () {
                            $(element).text(Math.floor(this.countNum));
                        },
                        complete: function () {
                            $(element).text(this.countNum);
                        }
                    });
            };


            //********************************************************************//
            //Render Profile Page
            //********************************************************************//

            getprofile().always(function () {

            });

            getprofile().fail(function () {


                $('h1').after('<div class="row bg-white"><ul class="alert alert-danger text-center"><li class="alert alert-danger">Sorry there is a network error <a href="">Click to refresh page</a></li></ul></div>');

            });

            getprofile().done(function (data) {


                $('h4').html(name.first_name + ' ' + name.last_name)


                // Render Pie Chart

                var pieData = data.pieData.filter(function (el) {

                    if (el.value != 0) {

                        return el;
                    }
                });

                var w = 150,
                    h = w,
                    c = w / 2,
                    r = 70,
                    inside = 65,

                    color = ['black', 'white']

                var vis = d3.select(".pie-chart")
                    .data([pieData])
                    .attr("width", w)
                    .attr("height", h)
                    .append("svg:g")
                    .attr("transform", "translate(" + c + "," + c + ")")

                var arc = d3.svg.arc()
                    .outerRadius(r)
                    .innerRadius(r - inside);

                var pie = d3.layout.pie()
                    .value(function (d) {
                        return d.value
                    });

                var arcs = vis.selectAll("g.slice")
                    .data(pie)
                    .enter()
                    .append("svg:g")
                    .attr("class", "slice");

                arcs.append("svg:path")
                    .attr("fill", function (d, i) {

                        return (d.data[1] == null && d.data['label'] == 'LOSS') ? 'white' : color[i];

                    })
                    .attr("d", arc)
                    .attr("stroke", "black")
                    .transition()
                    .duration(1000)
                    .attrTween('d', tweenPie);

                vis.append("svg:circle")
                    .attr("cx", 0)
                    .attr("cy", 0)
                    .attr("r", r - inside)
                    .attr("fill", "yellow");

                function tweenPie(finish) {
                    var start = {
                        startAngle: 0,
                        endAngle: 0
                    };
                    var i = d3.interpolate(start, finish);
                    return function (d) {
                        return arc(i(d));
                    };
                }// END oF pie

                animateNumbers('.win-percentage span', data.stats.winningPct);
                animateNumbers('.loss-percentage span', data.stats.losingPct);
                animateNumbers('.points', data.stats.points);
                animateNumbers('.matches', data.stats.matches);
                animateNumbers('.ranking', data.stats.ladder_pos);

            });


        }, // EO Ladder.profile

        show: function () {


            //********************************************************************//
            //Functions
            //********************************************************************//

            /**
             * Function to increase score input. Max value 3.
             */
            function counterUp(value) {

                var value = parseFloat(value);

                if (value < 3) {

                    var value = parseFloat(value) + 1;

                }

                return value;
            };

            /**
             * Function to decrease score input. Min value 0.
             */
            function counterDown(value) {

                var value = parseFloat(value);

                if (value > 0) {

                    value = value - 1;
                }

                return value;
            };

            /**
             * Function to check total score input does not exceed 5. Match can only be best out of 5 games.
             */
            function checkvalue() {

                var user_input = parseFloat($('input[name="user_score"]').val());

                var opponent_input = parseFloat($('input[name="opponent_score"]').val());

                var total = user_input + opponent_input;

                return total;
            };

            /**
             * Function to check score input are not equal before match result is entered, otherwise submit button is disabled.
             */
            function checksubmit() {

                var user_input = parseFloat($('input[name="user_score"]').val());

                var opponent_input = parseFloat($('input[name="opponent_score"]').val());

                (user_input == opponent_input) ? $('button[type="submit"]').attr('disabled', true) : $('button[type="submit"]').attr('disabled', false);
            };

            //********************************************************************//
            //Events
            //********************************************************************//

            /**
             * Append match related data to modal to display information about a match where a result is being entered.
             */
            $('.list-group').on('click', 'a', function () {

                $('.modal-body').prepend($(this).parent().parent().html());

                $('.modal-body a').remove();

                $('input[name="match_id"]').val($(this).data('matchid'));

                $('#smallModal').modal('show');

            });

            /**
             * Remove match related data when modal is closed.
             */
            $('#smallModal').on('hide.bs.modal', function () {

                $('.modal-body p, .modal-body h5').remove();

                $('form input[name="result_by_default"]').prop("checked", false);

                $('form .row:not(form .row:first-of-type)').hide();

                $('form input[name="user_score"], input[name="opponent_score"]').val(0);

            });

            /**
             * Show win / loss button as active when clicked and show next row in results form
             */
            $('.btn.win, .btn.loss').on('click', function () {

                $('.btn').removeClass('active');

                $(this).addClass('active');

                $('input[name="win"]').val($(this).val());

                $($('form .row')[1]).show(500);

            });

            /**
             * Increase value when user increases a match score
             */
            $('.user_score .score-button.up, .opponent_score .score-button.up').on('click', function () {

                if (checkvalue() < 5) {

                    var newvalue = counterUp($(this).next().val());

                    $(this).next().val(newvalue).trigger('change');

                }
                ;

            });

            /**
             * Decrease value when user increases a match score
             */
            $('.user_score .score-button.down, .opponent_score .score-button.down').on('click', function () {

                var newvalue = counterDown($(this).prev().val());

                $(this).prev().val(newvalue).trigger('change');

            });

            //********************************************************************//
            //Validation
            //********************************************************************//

            /**
             * When modal is opened only show first row of results form.
             */
            $('form .row:not(form .row:first-of-type)').hide();

            /**
             * Check to see if result is by default, else show the user the fields to enter the game scores for a match.
             */
            $('form input[name="result_by_default"]').on('change', function () {

                if ($(this).val() == 1) {

                    $($('form .row')[2]).show(500).hide(500);

                    $('button[type="submit"]').attr('disabled', false);

                } else {

                    $($('form .row')[2]).show(500);

                    $('button[type="submit"]').attr('disabled', true);
                }

            });

            /**
             * Check to see if match scores are not equal to each other. ie there is a winner for the match and enable submit button.
             */
            $('input[name="user_score"], input[name="opponent_score"]').on('change', function () {

                checksubmit();
            });

        } //EO Ladder.show
    }, // EO Ladder

    admin: {
        init: function () {
            // application-wide code
        },

        bookings: function () {

            $('.list-group').on('click', 'a', function () {

                $('input[name="booking_id"]').val($(this).data('bookingid'));

                $('.modal-body').append($(this).parent().html());

                $('.modal-body').find('p').addClass('text-center');

                $('.modal-body a').remove();

                $('#smallModal').modal('show');

            });

            /**
             * Remove any booking information, displayed in modal.
             */
            $('#smallModal').on('hide.bs.modal', function () {

                $('.modal-body h5').nextAll().remove();

            });
        },

        book: function () {


            /**
             * Attach any existing booking data to booking time options to inform admin/super-users making block booking
             * @param data - ajax return of collected booking data
             */
            function attach_data(data){

                console.log(data);

                if ((Object.keys(data).length) > 3) {

                    $('select[id="start_time"] option[value="'+data.timeslot_id+'"]').text(data.time+' BOOKED: '+data.player1+' - '+data.booking_description);
                    $('select[id="finish_time"] option[value="'+(data.timeslot_id-1)+'"]').text(data.time+' BOOKED: '+data.player1+' - '+data.booking_description);

                }else{

                    $('select[id="start_time"] option[value="'+data.timeslot_id+'"]').text(data.time);
                    $('select[id="finish_time"] option[value="'+(data.timeslot_id-1)+'"]').text(data.time);
                }
            }

            /**
             * Display the booking data based on selected court and selected date.
             */
            function show_bookings(){

                var selected_court = 'court_'+$('select[name="court"]').find(":selected").val();

                var selected_date = datepicker.datepicker('getFormattedDate');

                var post_date = {
                    'date': selected_date
                };

                window.SQUASH_APP.common.getdata().always(function () {

                });

                window.SQUASH_APP.common.getdata().fail(function () {

                    $('h1').after('<div id="booking-warn" class="row bg-white"><ul class="alert alert-danger text-center"><li class="alert alert-danger">Sorry there is a network error<a href="">Click to refresh page</a></li></ul></div>');

                });

                window.SQUASH_APP.common.getdata(post_date).done(function (data) {


                    $.each(data[selected_court], function (key, value) {

                        attach_data(data[selected_court][key]);

                    });


                });
            }

            var date = new Date();

            var add_six_months = date.setMonth(date.getMonth()+6);

            var six_months = new Date(add_six_months);

            console.log(six_months.getFullYear()+'-'+six_months.getMonth()+'-'+six_months.getDate());

            var datepicker = $('.input-group.date').datepicker({

                format: 'yyyy-mm-dd',
                startDate: "today",
                endDate: six_months.getFullYear()+'-'+six_months.getMonth()+'-'+six_months.getDate()

            });

            /**
             * Set selected date on Datepicker to the current date on page load
             */
            datepicker.datepicker("setDate", "0");

            var selected_date = datepicker.datepicker('getFormattedDate');

            $('input[name="date"]').val(selected_date);

            show_bookings();

            datepicker.datepicker().on('changeDate', function (e) {

                var selected_date = datepicker.datepicker('getFormattedDate');

                $('input[name="date"]').val(selected_date);

                show_bookings();


            });

            $('#court').change(function(e){

                show_bookings();

            });


            $('button[type="submit"]').attr('disabled', true);

            $('select[name="start_time"]').on("change", function () {

                $('select[name="finish_time"] option').attr('disabled', true);

                var value = ($(this).val() - 1);

                $('select[name="finish_time"] option:gt(' + value + ')').attr('disabled', false);
            })


            /**
             * Booking Description Required
             */
            $('input[name="description"]').on("change", function () {

                if ($(this).val() != null) {

                    $('button[type="submit"]').attr('disabled', false);
                }
            })


        },
        user: function () {

            $('#edit-user, #delete-user').hide();

            //Initial search engine for administrator to find user to delete or upgrade
            var engine = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: "../administrator/data-member?name=%QUERY"
            });

            engine.initialize();


            $('.typeahead').typeahead({
                minLength: 3,
                highlight: true
            }, {
                name: 'players',
                displayKey: 'name',
                source: engine.ttAdapter()
            });

            $('.typeahead').on('typeahead:selected', function (e, suggestion, dataset) {

                $('input[name="user_id"]').val(suggestion.id);

                $('input[name="first_name"]').val(suggestion.first_name);

                $('input[name="last_name"]').val(suggestion.last_name);

                $('input[name="email"]').val(suggestion.email);

                $('select[name="user_type"]').val(suggestion.user_type);

                $('input[name="user_type"]').val(suggestion.user_type);

                $('button[type="submit"]').attr('disabled', false);

                $('button.delete-user').attr('disabled', false);

                $('#edit-user, #delete-user').show(500);

            });

            //Open and close delete modal
            $('#deleteUserOpen').on('click', function (e) {

                $('#deleteModal').modal('show');

            });

        },
        notices: function () {

            //Input notice id into modal form to submit to delete a notice by the Administrator
            $('.pin').on('click', '.remove-notice', function () {

                $('input[name="notice_id"]').val($(this).data('noticeid'));

                $('#smallModal').modal('show');
            });
        },
    }
}; //EO Object

//Initiate the object on page load
UTIL = {
    exec: function (controller, action) {
        var ns = SQUASH_APP,
            action = (action === undefined) ? "init" : action;

        if (controller !== "" && ns[controller] && typeof ns[controller][action] == "function") {
            ns[controller][action]();
        }
    },

    init: function () {
        var body = document.body,
            controller = body.getAttribute("data-controller"),
            action = body.getAttribute("data-action");

        UTIL.exec("common");
        UTIL.exec(controller);
        UTIL.exec(controller, action);
    }
};

$(document).ready(UTIL.init);
