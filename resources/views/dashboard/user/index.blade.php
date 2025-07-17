@extends('layouts.dashboard')

@section('header')
<div class="mt-10 mb-4">
    <h2 class="text-3xl font-semibold text-royal-blue">Manajemen Pengguna</h2>
    <p class="text-dark-blue mt-1">Kelola data pengguna yang memiliki akses ke sistem, termasuk admin dan staf yang bertugas.</p>
</div>
@endsection

@section('content')
<div class="space-y-4">
    <div class="p-2 flex items-center justify-between">
        <h2 class="text-xl font-semibold text-royal-blue">Daftar Pengguna</h2>
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
                <th class="font-normal p-2">Role</th>
                <th class="font-normal p-2">Status Akun</th>
                <th class="font-normal p-2">Tgl Daftar</th>
                <th class="font-normal p-2">Aksi</th>
            </thead>
            <tbody id="user-content" data-edit-url="{{ route('dashboard.users.update', ':id') }}">

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
        const editUrlTemplate = userContent.dataset.editUrl

        const row = dataUser.map((user, index) => {
            const isVerified = user.is_email_verified
            const statusVerified = isVerified === 1 ?
                '<bold class="text-xs text-royal-blue bg-royal-blue/20 px-2 py-1 rounded">Aktif</bold>' :
                ' <bold class="text-xs text-red-500 bg-red-200 px-2 py-1 rounded">Tidak Aktif</bold>'

            const editUrl = editUrlTemplate.replace(':id', user.id)

            return (
                `
                    <tr class="hover:bg-dark-blue/20 divide-y divide-gray-200 text-gray-800 *:text-sm *:font-medium">
                        <td class="py-4 px-6">${ index + 1 }</td>
                        <td class="px-2 py-4 text-dark-blue">${ user.name }</td>
                        <td class="px-2 py-4 text-dark-blue">${ user.role }</td>
                        <td class="px-2 py-4 text-dark-blue">${ statusVerified }</td>
                        <td class="px-2 py-4 text-dark-blue">${user.created_at}</td>
                        <td class=" py-4 px-2">
                            <a href="${editUrl}" class="text-red-500 text-xs cursor-pointer">${user.is_email_verified ? "Non aktifkan" : ""}</a>
                        </td>
                    </tr>
                `
            )
        }).join(" ")

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
            const styleIsActive = isActive ? "bg-royal-blue text-white" : ""

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

    showFilterLimit()
    loadDataUser()
</script>
@endsection