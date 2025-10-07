function tocontroller(controller, active, dopdown, head_nav) {
    if (active != "") {
        $(".sub_reset").removeClass("active"); // hapus class aktif pada sub navbar
        $("[aria-expanded]").attr("aria-expanded", "false"); // reset semua jadi false
        $(".collapse").removeClass("show"); // hapus dropdown
        $("#" + active).addClass("active"); // tentukan navbar aktive
    }
    if (dopdown != "") {
        $("#" + dopdown).addClass("show"); // tambah class show pada dropdown
    }
    if (head_nav != '') {
        $("#" + head_nav).addClass("active"); // tambah class active pada sub navbar
    }
    loadform(controller);
}
function loadform(controller) {
    var base = BASE_URL;
    var url = base + "/" + controller;

    // Jika tidak ada di cache, lakukan AJAX request
    $("#contentdata").load(url, function (response, status, xhr) {
        hideLoader(); // Sembunyikan preloader setelah AJAX selesai

        if (status === "error") {
            $("#contentdata").html(
                `<div class="alert alert-danger">Gagal memuat konten.</div>`
            );
            return;
        }
        // Cek apakah response JSON dengan session expired
        try {
            let json = JSON.parse(response);
            if (json.session_expired) {
                swet_gagal(json.message);
                setTimeout(function () {
                    window.location.href = json.redirect;
                }, 1000); // 2000 ms = 2 detik delay
                return;
            }
        } catch (e) {
            // Bukan JSON? lanjut seperti biasa
        }
    });
}


function showLoader() {
	$("#preloader").show();
}

function hideLoader() {
	$("#preloader").hide();
}


function swet_sukses(pesan) {
	Swal.fire({
		icon: "success",
		title: pesan,
		showConfirmButton: false,
		timer: 1500,
	});
}
function swet_gagal(pesan) {
	Swal.fire({
		icon: "info",
		title: pesan,
		showConfirmButton: false,
		timer: 1500,
	});
}

function hapus(uuid, url_hapus, load_grid) {
	Swal.fire({
		icon: "question",
		title: "Apakah Anda Yakin!",
		text: "Data Ini Akan Di Hapus Secara Permanen",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		cancelButtonText: "Batal",
		confirmButtonText: "Ya !",
		reverseButtons: true,
	}).then((result) => {
		if (result.value) {
			$.ajax({
                type: "POST",
                url: url_hapus,
                method: "POST",
                dataType: "JSON",
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ), // CSRF token
                },
                data: {
                    uuid: uuid,
                    token: $("#token").val(),
                },
                success: function (data) {
                    if (data.hasil == "true") {
                        hideLoader();
                        swet_sukses(data.pesan);
                        loadform(load_grid);
                    } else {
                        Swal.fire({
                            icon: "info",
                            title: "Information",
                            text: data.pesan,
                        });
                    }
                },
            });
		} else if (result.dismiss === "cancel") {
			Swal.fire({
				icon: "info",
				title: "Information",
				html: "Batal Dihapus",
			});
		}
	});
}

function editform(route, uuid){
        var url = route + "/" + uuid;
        loadform(url)
}
