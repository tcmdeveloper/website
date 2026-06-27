{{-- Featured image --}}

            <div class="bg-gray-100 border border-zinc-300 rounded-sm shadow-sm p-4 py-10 flex justify-center items-center w-full mx-auto mb-8">


                {{-- Current image canvas --}}

                <div 
                    id="currentImageCanvas" 
                    class="flex flex-col items-center"
                >

                    {{-- Featured image --}}

                    <div class="relative group">

                        <img
                            src="{{ url('images/default-article.jpg') }}"
                            class="w-2xl"
                            alt="Default article image"
                        >

                        
                        {{-- Edit image --}}

                        <button
                            type="button"
                            @click="$refs.imageInput.click()"
                            class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100 cursor-pointer"
                            aria-label="Change image"
                        >

                            <x-heroicon-o-pencil class="h-8 w-8 text-white" />

                        </button>


                        {{-- Hidden image input --}}

                        <input
                            x-ref="imageInput"
                            id="imageInput"
                            name="featured_image"
                            type="file"
                            accept="image/*"
                            class="hidden"
                        />

                    
                    </div>


                    {{-- Featured image meta  --}}

                    <div class="mt-6 w-full flex flex-col space-y-5">

                        {{-- Caption --}}

                        <div>
                            <x-ui.input
                                name="featured_image_caption"
                                type="text"
                                size="sm"
                                placeholder="Image caption"
                                label="Image caption"
                                :value="old('featured_image_caption')"
                            />
                        </div>


                        {{-- Alt text --}}

                        <div>
                            <x-ui.input
                                name="featured_image_alt_text"
                                type="text"
                                size="sm"
                                label="Alt text"
                                placeholder="Alt text"
                                :value="old('featured_image_alt_text')"
                            />
                        </div>


                        {{-- Source --}}

                        <div>
                            <x-ui.input
                                name="featured_image_source"
                                type="text"
                                size="sm"
                                label="Image source"
                                placeholder="Image source"
                                :value="old('featured_image_source')"
                            />
                        </div>


                        {{-- Source URL --}}

                        <div>
                            <x-ui.input
                                name="featured_image_source_url"
                                type="text"
                                size="sm"
                                label="Link to source"
                                placeholder="Image source"
                                :value="old('featured_image_source_url')"
                            />
                        </div>

                    </div>

                </div>
                {{-- End of featured image canvas --}}


                {{-- Image cropper canvase --}}

                <div id="imageCropperCanvas" class="mb-6">
                    <img
                        id="preview"
                        class="max-w-xl rounded-lg"
                    >
                    <input type="hidden" name="cropped_image" id="croppedImage">
                    <x-ui.button variant="secondary" class="hidden!">Cancel</x-ui.button>
                </div>                

            </div>