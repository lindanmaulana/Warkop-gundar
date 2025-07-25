@extends('layouts.dashboard')

@section('header')
<div class="py-10 px-4 rounded-bl-2xl w-full shadow-md bg-white">
    <h2 class="text-3xl font-semibold text-primary">Manajemen Pengguna</h2>
    <p class="text-secondary mt-1">Kelola data pengguna yang memiliki akses ke sistem, termasuk admin dan staf yang bertugas.</p>
</div>
@endsection

@section('content')
@php
    $isSuperadmin = Auth::user()->role->value === "superadmin";
    $isAdmin = Auth::user()->role->value === "admin";
@endphp
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-primary">Daftar Pengguna</h2>
        <input id="filter-search" type="text" placeholder="Cari..." class="border border-dark-blue/20 rounded-lg px-4 py-1">
    </div>

    @if(session('success'))
    <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <p class="text-green-700 ">
            <strong class="bold">Success!</strong> {{session('success')}}
        </p>
    </div>
    @endif

    <div class="bg-white p-2 rounded-lg shadow-md shadow-dark-blue/10 py-4">
        <table class="w-full text-left rounded-md overflow-hidden">
            <thead class="*:text-gray-400  *:border-b *:border-dark-blue/10">
                <th class="font-normal py-2 px-6">No</th>
                <th class="font-normal p-2">Nama</th>
                <th class="font-normal p-2">Email</th>
                <th class="font-normal p-2">Role</th>
                <th class="font-normal p-2">Verifikasi Email</th>
                <th class="font-normal p-2">Status Akun</th>
                @if($isSuperadmin)
                    <th class="font-normal p-2">Aksi</th>
                @endif
            </thead>
            <tbody id="user-content" data-role-access="{{ $isAdmin }}">

            </tbody>
        </table>

        <div class="flex items-center justify-between py-6 px-4">
            <div class="flex items-center gap-2">
                <select name="" id="filter-limit" class="border border-dark-blue/20 rounded-md px-3 py-1 text-sm font-semibold"></select>
                <p class="text-sm font-semibold opacity-50">data per halaman</p>
            </div>

            <div id="filter-page" class="flex items-center gap-2">

            </div>
        </div>
    </div>
</div>
@endsection


@section("script")
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const urlParams = new URLSearchParams(window.location.search)

    const dataFilterLimit = [5, 10, 15, 20]


    function debounce(fn, delay) {
        let timeout;

        return function(...args) {
            clearTimeout(timeout)
            timeout = setTimeout(() => fn.apply(this, args), delay)
        }
    }

    const filterSearch = document.getElementById("filter-search")
    filterSearch.defaultValue = urlParams.get("keyword") ? urlParams.get("keyword").toString() : ""
    filterSearch.addEventListener("input", debounce(function() {
        const value = this.value

        switch (value) {
            case "":
                urlParams.delete("keyword")
                break;
            default:
                urlParams.set("keyword", value)
                urlParams.set("page", 1)
                break
        }

        updateURL()
        loadDataUser()
    }, 1000))

    document.getElementById("filter-limit").addEventListener("change", function() {
        const value = this.value
        urlParams.set("limit", value)
        urlParams.set("page", 1)

        updateURL()
        loadDataUser()
    })

    const showFilterLimit = () => {
        const urlParams = new URLSearchParams(window.location.search)
        const limitParams = urlParams.get("limit") || "5"

        const filterLimit = document.getElementById("filter-limit")
        filterLimit.innerHTML = ""

        const options = dataFilterLimit.map(limit => (
            `
            <option value="${limit}" ${limit == limitParams ? "selected" : ""}>${limit}</option>
            `
        )).join(" ")

        filterLimit.innerHTML = options
    }

    const loadDataUser = async () => {
        try {
            const response = await fetch(`/api/v1/users?${urlParams.toString()}`, {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                }
            })

            const result = await response.json()
            const currentPage = result.data.current_page
            const pages = result.data.links

            const data = result.data.data
            showUserContent(data)
            showFilterPage(pages)

            return result
        } catch (err) {
            console.log({
                err
            })
        }
    }

    const showUserContent = (dataUser) => {
        const userContent = document.getElementById("user-content")
        userContent.innerHTML = ""
        const isAdmin = userContent.dataset.roleAccess

        const row = dataUser.length > 0 ? dataUser.map((user, index) => {
            const isVerified = user.is_email_verified
            const statusVerified = isVerified === 1 ?
                '<bold class="text-xs text-royal-blue bg-royal-blue/20 px-2 py-1 rounded">Terverifikasi</bold>' :
                ' <bold class="text-xs text-red-500 bg-red-200 px-2 py-1 rounded">Belum</bold>'

            const statusAccount = user.is_suspended ? '<bold class="text-red-500 bg-red-200 px-3 py-1 text-xs rounded">Ditangguhkan</bold>' : '<bold class="text-green-500 bg-green-200 px-3 py-1 text-xs rounded">Aktif</bold>'
            let showActionSuspended = user.role != "superadmin" ? `<button onclick="handleConfirmSuspended(${user.id}, ${user.is_suspended})" class="text-red-500 text-xs cursor-pointer">${user.is_suspended ? "Aktifkan" : "Non aktifkan"}</button>` : ""
            let showActionEdit = user.role != "superadmin" ? `<a href="/dashboard/users/update/${user.id}" class="text-green-500 text-xs cursor-pointer">Edit</a>` : ""

            if(isAdmin) {
                showActionSuspended = ""
                showActionEdit = ""
            }

            return (
                `
                    <tr class="hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800 *:text-sm *:font-medium">
                        <td class="py-4 px-6">${ index + 1 }</td>
                        <td class="px-2 py-4 text-dark-blue">${ user.name }</td>
                        <td class="px-2 py-4 text-dark-blue">${ user.email }</td>
                        <td class="px-2 py-4 text-dark-blue">${ user.role }</td>
                        <td class="px-2 py-4 text-dark-blue">${ statusVerified }</td>
                        <td class="px-2 py-4 text-dark-blue">${statusAccount}</td>
                        <td class=" py-4 px-2">
                            <div class="flex items-center gap-2">
                                ${showActionEdit}
                                ${showActionSuspended}
                            </div>
                        </td>
                    </tr>
                `
            )
        }).join(" ") : (
            `
            <tr>
                <td colspan="6" class=" py-4">
                    <div class="flex items-center justify-center gap-2 text-red-500">
                        <x-icon name="notfound" class="mt-px" />  <bold class="text-base">Tidak ada data ditemukan.</bold>
                    </div>
                </td>
            </tr>
            `
        )
        userContent.innerHTML = row
    }

    const showFilterPage = (pages) => {
        const urlParams = new URLSearchParams(window.location.search)
        const pageParams = urlParams.get("page")
        const filterPage = document.getElementById("filter-page")
        filterPage.innerHTML = ""

        const buttonPagination = pages.map(page => {
            const isUrl = page.url
            const isButton = page.label.length
            const isPaginationControl = isButton > 1

            const isActive = isUrl && pageParams == page.label
            const isDisabled = !isUrl

            const styleIsDisabled = isDisabled ? "cursor-not-allowed opacity-50" : "cursor-pointer"
            const styleIsActive = isActive ? "bg-secondary text-white" : ""

            return (
                `
                    <button onclick="handleFilterPage('${isUrl}')" ${isDisabled || isActive ? "disabled" : ""} class="border px-3 py-1 rounded text-sm ${styleIsActive} ${styleIsDisabled}" >${page.label}</button>
                `
            )
        }).join(" ")

        filterPage.innerHTML = buttonPagination
    }

    const handleFilterPage = (url) => {
        const urlObj = new URL(url)
        const params = new URLSearchParams(urlObj.search)
        const page = params.get("page")

        urlParams.set("page", page)

        updateURL()
        loadDataUser()
    }

    const updateURL = () => {
        const newURL = `${window.location.pathname}?${urlParams.toString()}`

        window.history.replaceState({}, '', newURL)
    }

    const handleConfirmSuspended = (userId, is_suspended) => {
        const urlParams = new URLSearchParams(window.location.search)
        let question = is_suspended ? "Apakah Anda yakin ingin mengaktifkan kembali akun ini? 🟢 Pengguna akan mendapatkan kembali akses ke sistem." : "Apakah Anda benar-benar yakin ingin menangguhkan akun ini? 🔒 Pengguna akan kehilangan akses ke sistem."
        let suspended = is_suspended ? "0" : "1"

        Swall.fire({
            title: question,
            showCancelButton: true,
            confirmButtonText: "Yes",
        }).then((result) => {
            if (result.isConfirmed) {
                suspendedAccount(suspended, userId)
                window.location.href = `/dashboard/users?${urlParams.toString()}`
                Swall.fire("Updated", "", "success");
            }
        })
    }

    const suspendedAccount = async (is_suspended, userId) => {
        try {
            const response = await fetch(`/api/v1/users/suspended/${userId}`, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({is_suspended})
            })

            Swall.fire({
                title: "Status akun berhasil di ubah",
                icon: "success"
            })
        } catch (err) {
            Swall.fire({
                title: "Status akun gagal di ubah!",
                icon: "error"
            })
        }
    }

    showFilterLimit()
    loadDataUser()
</script>
@endsection