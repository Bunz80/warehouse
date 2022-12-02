<x-filament::widget class="filament-filament-info-widget">
    <x-filament::card class="relative">
        <div class="relative h-12 flex flex-col justify-center items-center space-y-2">
            <div class="space-y-1">
                <img src="{{ asset('/images/logo.png') }}" alt="Logo" class="h-10" >
            </div>

            <div class="text-sm flex space-x-2 rtl:space-x-reverse">
                <a @class([
                        'text-gray-600 hover:text-primary-500 focus:outline-none focus:underline',
                        'dark:text-gray-300 dark:hover:text-primary-500' => config('filament.dark_mode'),
                    ])
                > 
                    Ver:{{ env('APP_VERSION') }} of {{ env('APP_VERSION_DATE') }}
                    <span> &bull; </span>
                    System created by S.Pisani
                </a>
            </div>
        </div>

        <svg class="h-16 w-16 absolute right-0 rtl:right-auto rtl:left-0 bottom-0" xmlns="http://www.w3.org/2000/svg"
             width="300.000000pt" height="418.000000pt" viewBox="0 0 300.000000 418.000000"
             preserveAspectRatio="xMidYMid meet">
            <g @class([
                'dark:fill-current' => config('filament.dark_mode'),
            ]) transform="translate(0 418) scale(.1 -.1)" fill="#111827" xmlns="http://www.w3.org/2000/svg">
                <path d="m2735 4138c-50-59-87-174-102-313-13-135-22-170-49-193l-27-23 27-28c23-24 26-35 26-102 0-42-3-78-6-82-4-3-22 5-40 20-19 14-34 23-34 20s14-31 30-62c35-66 35-74 13-206-17-106-16-104-66-84-18 7-17 5 2-34 12-22 22-58 23-79 2-34-9-147-18-197-2-11-4-51-5-88l-2-69-53 53c-30 29-54 50-54 47 0-4 23-45 51-92l52-86-7-87c-4-48-10-91-12-95-3-5-25 5-49 22-24 16-46 30-50 30-3 0 18-30 46-67l51-67-4-66c-3-36-14-84-25-107-17-34-18-44-8-53 33-28 23-119-15-140-10-6-57-10-104-10h-85l-3 40c-4 49-22 49-26 0-2-19-5-36-7-38-6-7-186-24-200-18-9 3-15 18-15 35 0 16-5 33-11 36-17 11-20 4-28-71 0-6-31-16-68-22-38-6-82-14-99-18-30-6-33-4-44 32-19 60-30 64-30 11 0-58-11-64-138-74l-87-6 43 46c23 26 42 55 42 65 0 30 37 69 77 83 32 10 35 13 20 24-23 17-22 20 18 81 19 29 44 73 55 98l19 46-27-6c-65-16-137-49-245-114-65-38-117-64-117-59s12 58 26 117c26 107 30 114 92 162l27 21-37-3c-41-4-42-3-28 57 12 56 19 92 25 150 6 46 4 51-14 52-13 1-35-16-62-48-39-46-43-49-59-34s-18 13-24-27c-9-59-60-145-100-169-19-11-41-36-51-58-9-21-28-44-41-50s-24-19-25-28c-2-38-10-73-42-169-33-98-34-99-68-102s-35-2-48 48c-18 68-61 113-130 134-90 27-159 16-203-31-42-45-53-95-34-153 9-27 16-51 16-53 0-3-33-8-72-12-112-11-450-105-480-134-8-7 30-154 55-210l14-34 44 62 43 61 52-51 51-51 22 36 22 35 72-80-24-27c-13-15-27-31-30-35-4-5-20-2-35 6-16 8-42 17-59 18-27 4-31 0-46-41-10-25-20-53-23-62-4-15-13-14-86 13-44 16-85 29-91 29-7 0-9-38-7-108l4-107h34c43 1 198 13 225 19 26 5 77 12 284 42l104 14 29-27 28-27v27c0 33 29 47 95 47 32 0 51-7 74-26l31-26v41c0 48 8 51 45 14 48-48 65-98 65-195 0-52 6-103 14-125 16-42 23-113 32-338 4-98 13-174 25-220 32-116 27-144-32-212-76-87-78-93-29-94 22-1 46-2 53-3 6 0 14-20 17-43l5-41 30 24 30 23 36-35 36-36 13 34c7 21 13 112 15 248 2 118 6 267 9 330s8 183 12 265c3 83 8 151 9 153 2 2 62 14 135 27l131 23-7-67c-3-36-8-68-9-71-2-3-6-37-10-75-4-39-9-77-10-85-2-8-6-71-10-140-7-119-12-141-53-223-2-4 8-5 23-1l28 7-5-90c-4-85-6-93-51-168-26-44-47-89-47-101s-3-29-6-37c-5-13-1-13 33-2 24 9 75 13 139 12l100-2 3 110c1 61 3 124 5 140 2 17 7 95 10 175 9 197 15 260 32 360 9 47 18 105 21 128 3 24 11 45 17 47s61 6 121 10c61 3 129 8 151 11 43 5 60-8 101-72 9-16 11-10 6 32-5 47-4 51 18 57 52 14 104 7 143-19 22-15 37-21 34-13-4 8-9 22-13 31-5 14 2 18 42 23 26 4 56 9 66 12 17 4 18 0 11-55-4-39-1-82 9-128 9-38 18-118 22-178s13-143 21-184c17-89 19-265 3-295-7-12-31-43-55-69s-44-51-44-57c0-5-3-16-6-24-9-26 31-29 87-7 64 24 59 23 59 7 0-14 123-52 139-43 20 13 22 65 4 127-14 50-18 107-19 259-1 107-5 281-10 386l-7 192 22 12c13 6 24 10 26 8 1-2 6-70 10-153 4-82 8-161 10-175 1-14 6-61 9-105 4-44 19-132 32-195 25-114 26-163 7-282-6-41-4-43 45-29 18 6 63 11 100 12s70 4 73 5c5 3-4 105-15 179-3 19-8 70-11 113-3 42-15 111-27 152-12 45-21 103-20 145 0 39-6 90-13 115-8 25-16 58-20 74-6 28-5 28 24 23 17-4 31-5 31-3s-12 25-26 51l-27 47 8 577c4 317 9 587 10 601 2 14 4 29 4 34 1 5 18 24 38 43l36 35-39-3-39-4v101c1 84 4 106 22 136l21 36-25 7-25 6 8 82c16 161 7 497-17 631-19 111-24 191-13 221 4 8 26 29 51 46 52 36 52 46 0 24-63-26-63-25-72 88-5 56-9 131-10 167s-6 74-12 85c-17 31-41 124-48 185-10 81-17 88-50 48zm38-213c1-44-1-92-6-107-7-25-5-28 18-28s25-3 25-48c0-31-9-65-24-96l-24-46 66 12-10-46c-7-35-6-58 7-101 8-30 21-101 27-158 9-85 8-108-4-137-13-32-13-39 5-79 12-27 21-77 25-128l5-83-39-36-38-37 37 5 37 4v-124c0-126-5-148-43-189l-20-23h31 32v-87c0-67-5-99-21-133-11-24-16-46-12-47 28-10 29-26 21-338-5-176-12-489-16-695l-8-375 28-115c16-63 35-168 43-233 14-113 40-272 51-310 5-16 1-17-33-11-21 4-51 13-66 21-26 13-31 13-47-2-18-17-18-16-3 33 15 48 15 56-2 131l-18 80 25 58c25 57 45 133 31 120-3-4-21-33-39-65-19-31-34-52-34-47 0 6-5 53-10 105-6 52-14 184-19 293s-11 200-14 203c-2 3-23-3-46-12-67-27-75-53-67-213 4-74 9-162 13-194l6-60-27 7c-16 4-26 3-24-1 38-74 41-93 44-218 3-111 18-228 40-299 5-14-8-8-52 23-31 22-61 41-67 41-5 0-31-11-59-25-27-14-53-25-56-25-4 0 20 26 51 58 49 49 58 65 60 98 4 85-15 404-30 484-8 47-16 137-17 200s-8 128-14 143l-12 29-75-7c-72-7-74-7-100 21-22 23-28 26-35 14-6-8-10-18-10-22 0-13-44-36-82-42-36-7-37-6-49 34-7 22-15 40-19 40s-11-20-14-44l-7-44-72-7c-40-4-101-5-137-3-62 3-65 4-85 39l-20 37-23-74c-12-40-29-107-37-150-8-42-23-91-34-109-15-26-17-41-10-76 10-53 11-150 3-202-6-32-10-37-27-31-15 5-19 3-14-9 21-55 27-110 24-238l-2-145-35 13c-54 19-82 15-133-17-26-16-47-27-47-25 0 3 21 37 46 75 55 82 63 116 64 275 0 77 7 153 19 213 14 65 18 113 14 165-5 66-3 79 21 126 31 63 31 61 7 61-17 0-18 5-11 38 4 20 10 52 14 70l6 32h-50c-47 0-50 2-50 25 0 14-4 25-10 25-5 0-10-4-10-9 0-15-66-51-137-74-75-25-96-47-115-119-10-38-10-60 1-109 12-60 12-63-12-86l-24-24 20-57c16-47 18-70 11-127-10-93-11-309-2-414l8-84-23 20c-28 26-92 59-133 67l-32 7 45 42c29 28 45 51 45 67 1 74-20 338-27 350-4 7-12 77-17 154-5 78-11 164-14 192-4 37-1 60 10 82 21 40 20 42-7 42-21 0-24 7-35 73-19 111-42 150-110 184l-57 29-40-23c-26-15-57-23-87-23-26 0-93-9-148-20-85-16-263-40-482-65-27-3-48-3-48 0 0 15 418 192 590 250 129 44 217 86 199 95-17 9-147 26-319 40-245 21-426 55-479 89-9 6 10 18 67 40 103 39 162 52 241 53 41 0 80 7 109 20 49 22 401 85 494 89l56 2 17 64c9 34 33 89 52 120 20 32 42 73 49 91 8 18 25 37 39 42 17 6 26 20 31 45 4 22 41 82 101 162 52 71 97 130 100 133 11 12-11-100-33-161-13-37-24-81-24-99v-32l-35 21c-41 24-44 15-10-35 30-43 30-50 9-165-9-49-14-92-11-95s54 25 114 62c111 69 243 137 251 129 4-4-37-60-89-122-55-65-169-286-155-301 3-2 55 1 116 7 96 9 116 8 141-5 28-14 31-14 59 10 31 25 69 40 133 51 33 5 38 3 48-19l12-25 34 29c33 30 36 30 147 30 62 0 133 3 158 6l45 6 13-46c15-54 30-60 30-11 0 19 6 39 13 44 80 64 87 74 97 151 5 41 10 117 10 169v94l34-6 34-5-30 48-30 49 11 173c21 298 29 364 49 411 14 32 18 58 15 90-3 26 4 101 15 169s23 165 27 217c3 52 12 106 20 121 12 23 12 33-2 71-13 38-14 58-3 139 17 139 38 235 68 309l26 66 14-55c7-30 14-91 15-135zm34-92c-3-10-5-4-5 12 0 17 2 24 5 18 2-7 2-21 0-30zm-181-238c1-8-2-13-7-9-5 3-9 12-9 21 0 19 13 9 16-12zm229-74c3-5 1-12-5-16-5-3-10 1-10 9 0 18 6 21 15 7zm-298-513c-3-7-5-2-5 12s2 19 5 13c2-7 2-19 0-25zm335-500c-7-7-12-8-12-2 0 14 12 26 19 19 2-3-1-11-7-17zm-1462-169c0-5-4-9-10-9-5 0-10 7-10 16 0 8 5 12 10 9 6-3 10-10 10-16zm1078-47c-3-14-6-14-16 6-7 12-10 27-6 33 9 15 27-16 22-39zm-1298-12c-10-19-30-36-30-26 0 16 24 55 31 50 5-3 5-14-1-24zm1681-53c-8-8-11-7-11 4 0 20 13 34 18 19 3-7-1-17-7-23zm-1748-22c-3-9-8-14-10-11-3 3-2 9 2 15 9 16 15 13 8-4zm-329-119c18-8 43-24 55-37 23-24 65-98 59-104-4-4-300-55-318-55-9 0-11 21-8 76 3 71 6 78 36 105 27 25 41 29 87 29 31 0 71-6 89-14zm810-55c3-5-1-11-9-14-9-4-12-1-8 9 6 16 10 17 17 5zm366-169c0-13-12-22-22-16s-1 24 13 24c5 0 9-4 9-8zm-243-58c-3-4-14-3-24 0-15 6-15 8-3 16 16 10 39-4 27-16zm-1567-164c0-3-15-23-34-45-32-37-34-38-44-18-6 11-14 38-18 60l-6 40 51-16c28-9 51-18 51-21zm139-20h26l-27-26-26-26-31 23c-40 29-40 43 0 35 17-3 43-6 58-6zm174-24c15-2 46-3 69-4l42-2-22-22c-21-22-23-22-51-5-39 23-50 22-80-7l-26-25-34 41-35 41 55-8c30-4 66-8 82-9zm235-22c28-4 52-10 52-14 0-12-163-60-220-65-30-2-71-9-90-15-19-5-36-10-38-10-4 0 37 70 48 83 3 4 24-5 45-21l39-28 37 38c38 40 43 41 127 32zm-269-124c15 0 21-4 16-9-9-9-259-111-270-111-3 0-1 8 5 18 5 9 16 34 25 56 16 41 8 40 103 20 21-4 33 0 51 20 17 18 28 23 35 16 5-5 21-10 35-10zm-280-126c5-5 5-5-51-37-26-15-50-36-53-48-14-43-30-12-33 64l-3 76 68-25c37-14 70-28 72-30zm721-9c0-8-4-15-9-15-13 0-22 16-14 24 11 11 23 6 23-9zm1409-239c7-9 8-17 2-20-14-9-41 4-41 20 0 18 24 18 39 0zm-1242-48c-3-7-5-2-5 12s2 19 5 13c2-7 2-19 0-25zm1063 11c0-5-7-9-15-9-15 0-20 12-9 23 8 8 24-1 24-14zm730-919c0-5-15-10-32-9-28 0-30 2-13 9 28 12 45 12 45 0zm-1705-30c3-5 2-10-4-10-5 0-13 5-16 10-3 6-2 10 4 10 5 0 13-4 16-10zm412-16c-3-3-12-4-19-1-8 3-5 6 6 6 11 1 17-2 13-5z"/>
                <path d="m2746 3379c-3-17-6-60-6-95 0-59 2-64 21-64 21 0 22 3 15 88-8 94-19 122-30 71z"/>
                <path d="m2706 2889c-8-44-6-130 4-154 6-13 9 16 9 83 1 56-1 102-3 102s-6-14-10-31z"/>
                <path d="m2741 2590c0-30 6-73 13-95l12-40 8 33c9 38 0 97-19 132-14 24-14 22-14-30z"/>
                <path d="m2610 2562c0-5 13-27 29-50 34-46 58-66 48-38-4 10-7 21-7 26s-12 22-26 39c-25 30-44 40-44 23z"/>
                <path d="m2732 2310c-11-18-9-26 14-58l26-37-7 45c-10 63-18 74-33 50z"/>
                <path d="m2640 2305c0-4 12-30 28-58 28-53 46-43 18 11-15 29-46 61-46 47z"/>
                <path d="m2665 2079c8-39 63-164 70-158 7 8-20 111-38 146-25 47-41 53-32 12z"/>
                <path d="m1186 2041-26-51h24c21 0 25 6 31 46 3 25 4 48 1 51-2 3-16-18-30-46z"/>
                <path d="m2545 1963c3-26 9-61 12-78l6-30 8 25c4 14 7 44 8 67 1 33-3 45-20 53-20 11-20 10-14-37z"/>
                <path d="m1017 1828c-49-47-80-113-73-153 16-84 131-123 209-70 94 65 93 188-4 237-53 28-92 23-132-14zm141-28c49-46 33-131-31-170-40-25-64-25-104 0-56 34-53 96 7 160 35 37 94 41 128 10z"/>
                <path d="m1074 1754c3-9 6-24 6-35 0-24 9-24 39 2 23 19 23 20 5 34-26 20-58 20-50-1z"/>
                <path d="m688 1791c18-17 41-31 51-31 11 0 27-3 37-7 12-4 15-3 10 5-8 14-69 46-106 56-24 7-23 6 8-23z"/>
                <path d="m2675 1761c22-10 51-21 65-25l25-7-28 25c-20 19-39 26-65 25h-37l40-18z"/>
                <path d="m1496 1715c-3-9-6-30-6-46 0-24 4-29 24-29 23 0 24 0 9 42-18 52-19 53-27 33z"/>
                <path d="m2310 1712c0-37 58-132 82-132 10 0-33 85-61 120-11 14-21 19-21 12z"/>
                <path d="m1652 1680c9-29 77-95 86-85 10 10-72 115-89 115-5 0-3-14 3-30z"/>
                <path d="m1320 1684c0-14 71-116 77-111 8 9-27 73-53 98-13 12-24 18-24 13z"/>
                <path d="m2174 1668c12-54 25-75 60-96 20-12 36-19 36-16 0 8-48 78-75 109l-26 30 5-27z"/>
                <path d="m2e3 1624c5-33 16-55 40-77 31-31 32-31 25-7-12 41-56 130-64 130-4 0-5-21-1-46z"/>
                <path d="m2450 1644c0-17 62-133 80-149l20-18-19 55c-27 78-49 118-66 118-8 0-15-3-15-6z"/>
                <path d="m1586 1607c-21-28-56-105-56-125 0-13 44 37 60 68 18 37 34 90 26 90-3 0-16-15-30-33z"/>
                <path d="m1793 1575c3-11 11-42 18-70 18-76 30-79 27-6-3 54-7 66-27 79-22 15-23 15-18-3z"/>
                <path d="m1254 1545c4-16 19-55 34-85 23-47 27-52 30-30 6 37-15 94-44 121l-27 24 7-30z"/>
                <path d="m2722 1538c3-7 13-37 22-65 18-55 22-59 46-43 12 7 8 18-24 64-37 54-53 70-44 44z"/>
                <path d="m1671 1484c-11-15-21-38-21-53 1-22 4-19 26 22 29 56 26 71-5 31z"/>
                <path d="m2351 1446c21-35 69-75 69-56 0 13-69 90-81 90-5 0 1-15 12-34z"/>
                <path d="m1956 1452c-2-4 11-28 30-51 37-48 42-43 18 19-15 37-36 51-48 32z"/>
                <path d="m2490 1446c0-27 72-126 92-126 14 0-29 74-61 104-17 17-31 26-31 22z"/>
                <path d="m2112 1391c3-27 15-64 27-83l22-33-6 48c-7 49-31 117-41 117-4 0-4-22-2-49z"/>
                <path d="m1205 1358c-25-28-53-65-61-81-14-27-14-28 3-18 32 17 82 75 98 114 8 20 13 37 11 37s-25-23-51-52z"/>
                <path d="m1485 1363c8-42 29-88 46-99 16-9 2 50-22 99-25 47-33 47-24 0z"/>
                <path d="m2692 1325c-14-47-15-94-3-114 13-20 23 38 19 104l-3 50-13-40z"/>
                <path d="m1675 1310c12-39 32-75 51-91 15-13 16-12 9 11-12 39-32 75-51 91-15 13-16 12-9-11z"/>
                <path d="m2250 1325c0-6 63-63 94-85l21-14-19 29c-41 63-52 74-73 75-13 0-23-2-23-5z"/>
                <path d="m1347 1272c-17-19-30-50-41-97l-7-30 21 25c36 44 72 120 57 120-8 0-21-8-30-18z"/>
                <path d="m1896 1265c-10-16-16-45-15-82 1-57 1-57 14-28 13 31 30 135 22 135-3 0-12-11-21-25z"/>
                <path d="m1997 1253c-15-18-16-26-5-74l12-54 8 45c5 25 7 58 6 74-3 28-3 28-21 9z"/>
                <path d="m1216 1198c-18-28-50-128-43-135 9-8 46 63 56 107 12 53 8 60-13 28z"/>
                <path d="m2446 1201c19-18 109-70 113-65 3 2-11 20-29 39-24 25-43 35-64 35-18 0-26-4-20-9z"/>
                <path d="m712 2047-32-24 23-21c31-29 33-28 47 14 16 49 2 60-38 31z"/>
            </g>
        </svg>
    </x-filament::card>
</x-filament::widget>
