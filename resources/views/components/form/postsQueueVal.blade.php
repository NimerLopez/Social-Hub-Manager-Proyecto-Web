<div x-data="{ showScheduler: false }">  <!--alpne validacion -->
                        <div class="mb-4">       
                            <button
                                type="button"
                                x-on:click="showScheduler = !showScheduler"
                                class="px-4 py-2 bg-black text-white rounded mr-2 mb-2"
                            >
                                Programar Publicación
                            </button>
                        </div>

                        <div x-show="showScheduler" class="mt-4">
                            <div class="mb-4">
                                <label for="schedule-date" class="block font-semibold">Fecha de Publicación</label>
                                <input type="date" name="schedule-date" id="schedule-date" class="w-full p-2 border rounded">
                            </div>
                        <div class="mb-4">
                            <label for="schedule-time" class="block font-semibold">Hora de Publicación</label>
                            <input type="time" name="schedule-time" id="schedule-time" class="w-full p-2 border rounded">
                        </div>
                        </div>
</div><!-- fin alpne validacion -->