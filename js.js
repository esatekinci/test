$(document).ready(function () {
    // Tarih ve saat bilgisini güncelleme
    function updateDateTime() {
        var dateTime = new Date();
        var date = dateTime.toLocaleDateString();
        var time = dateTime.toLocaleTimeString();
        var dateTimeStr = date + ' ' + time;
        $('#date-time').text(dateTimeStr);
    }

    // Oturum kontrolü yap
    $.ajax({
        url: 'check-session.php',
        type: 'GET',
        success: function (response) {
            if (response === "success") {
                var username = "";
                // Kullanıcı adını alma
                $.ajax({
                    url: 'islemler.php',
                    type: 'GET',
                    data: {
                        action: 'getUsername'
                    },
                    success: function (response) {
                        username = response;
                        // Talepleri getir
                        $.ajax({
                            url: 'get-talepler.php',
                            type: 'GET',
                            success: function (response) {
                                var talepler = JSON.parse(response);
                                var tableBody = $('#talepler-table tbody');

                                talepler.forEach(function (talep) {
                                    if (talep.durum === 'Beklemede' || talep.durum === 'İşlemde') {
                                        var row = '<tr id="' + talep.talep_id + '">' +
                                            '<td>' + talep.kullanici_adi + '</td>' +
                                            '<td>' + talep.bonus_adi + '</td>' +
                                            '<td>' + talep.durum + '</td>';

                                        // Eklenti tarihini hesapla
                                        var eklemeTarihi = new Date(talep.ekleme_tarihi);
                                        var now = new Date();
                                        var minutesPassed = Math.floor((now - eklemeTarihi) / 60000);
                                        var formattedTime = minutesPassed + ' dakika önce';

                                        row += '<td>' + formattedTime + '</td>';

                                        if (talep.username === username) {
                                            row += '<td>' +
                                                '<button class="onayla-btn">Onayla</button>' +
                                                '<button class="reddet-btn">Reddet</button>' +
                                                '</td>';
                                        } else if (talep.username === null) {
                                            row += '<td><button class="isleme-al-btn">İşleme Al</button></td>';
                                        } else {
                                            row += '<td>' + talep.username + '</td>';
                                        }

                                        row += '</tr>';

                                        tableBody.append(row);
                                    }
                                });

                                // İşleme Al butonlarına tıklama olayı
                                $(document).on('click', '.isleme-al-btn', function () {
                                    var talepId = $(this).closest('tr').attr('id');
                                    var clickedButton = $(this); // Değişken olarak sakla

                                    // AJAX isteği ile talebi güncelle
                                    $.ajax({
                                        url: 'islemler.php',
                                        type: 'POST',
                                        data: {
                                            action: 'updateTalep',
                                            talepId: talepId,
                                            durum: '1',
                                            username: username
                                        },
                                        success: function (response) {
                                            // İstek başarılı ise yapılacak işlemler
                                            console.log(response);
                                            // Satırı güncelle
                                            var updatedRow = clickedButton.closest('tr');
                                            updatedRow.find('td:nth-child(3)').text('İşlemde');
                                            updatedRow.find('td:last-child').html('<button class="onayla-btn">Onayla</button><button class="reddet-btn">Reddet</button>');
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            console.log(jqXHR.responseText);
                                            console.log(textStatus);
                                            console.log(errorThrown);

                                        }
                                    });
                                });

                                // Onayla butonlarına tıklama olayı
                                $(document).on('click', '.onayla-btn', function () {
                                    var talepId = $(this).closest('tr').attr('id');
                                    var clickedButton = $(this); // Değişken olarak sakla

                                    // AJAX isteği ile talebi güncelle
                                    $.ajax({
                                        url: 'islemler.php',
                                        type: 'POST',
                                        data: {
                                            action: 'updateTalep',
                                            talepId: talepId,
                                            durum: '2',
                                            username: username
                                        },
                                        success: function (response) {
                                            // İstek başarılı ise yapılacak işlemler
                                            console.log(response);
                                            // Satırı güncelle
                                            var updatedRow = clickedButton.closest('tr');
                                            updatedRow.find('td:nth-child(3)').text('Onaylandı');
                                            updatedRow.find('td:last-child').html(username);
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            console.log(jqXHR.responseText);
                                            console.log(textStatus);
                                            console.log(errorThrown);
                                        }
                                    });
                                });

                                // Reddet butonlarına tıklama olayı
                                $(document).on('click', '.reddet-btn', function () {
                                    var talepId = $(this).closest('tr').attr('id');
                                    var clickedButton = $(this); // Değişken olarak sakla

                                    // AJAX isteği ile talebi güncelle
                                    $.ajax({
                                        url: 'islemler.php',
                                        type: 'POST',
                                        data: {
                                            action: 'updateTalep',
                                            talepId: talepId,
                                            durum: '3',
                                            username: username
                                        },
                                        success: function (response) {
                                            // İstek başarılı ise yapılacak işlemler
                                            console.log(response);
                                            // Satırı güncelle
                                            var updatedRow = clickedButton.closest('tr');
                                            updatedRow.find('td:nth-child(3)').text('Reddedildi');
                                            updatedRow.find('td:last-child').html(username);
                                        },
                                        error: function (jqXHR, textStatus, errorThrown) {
                                            console.log(jqXHR.responseText);
                                            console.log(textStatus);
                                            console.log(errorThrown);
                                        }
                                    });
                                });
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                console.log(jqXHR.responseText);
                                console.log(textStatus);
                                console.log(errorThrown);
                            }
                        });
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR.responseText);
                        console.log(textStatus);
                        console.log(errorThrown);
                    }
                });
            } else {
                window.location.href = "login.html";
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });

    // Tarih ve saat bilgisini güncelleme işlevini her 1 saniyede bir çağır
    setInterval(updateDateTime, 1000);
});
// Sorgula Butonu
$(document).ready(function () {
    $('#pending-form').submit(function (event) {
        event.preventDefault(); // Formun varsayılan gönderme davranışını engelleme
        var username = $('#pending-username').val(); // Kullanıcı adını al

        // AJAX isteği ile veritabanında kullanıcı adının kontrol edilmesi
        $.ajax({
            url: 'check-talep.php',
            type: 'POST',
            data: {
                username: username
            },
            success: function (response) {
                if (response === 'exists') {
                    alert('Kullanıcı adı mevcut'); // Kullanıcı adı veritabanında varsa mesaj gösterilebilir
                } else {
                    alert('Kullanıcı adı mevcut değil'); // Kullanıcı adı veritabanında yoksa mesaj gösterilebilir
                }
            },
            error: function (xhr, status, error) {
                alert('Hata: ' + error); // Hata durumunda hata mesajını göster
            }
        });
    });
});

// Çıkış Yap butonuna tıklama olayı
$('#logout-btn').click(function () {
    $.ajax({
        url: 'logout.php',
        type: 'GET',
        success: function (response) {
            // Oturum başarıyla sonlandırıldı, giriş sayfasına yönlendir
            window.location.href = "login.html";
        },
        error: function (xhr, status, error) {
            console.log('Hata: ' + error);
        }
    });
});
