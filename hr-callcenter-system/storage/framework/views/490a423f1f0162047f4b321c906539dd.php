<?php
    $data = $this->getViewData();
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['show'] ?? false): ?>
    <?php if (isset($component)) { $__componentOriginalb525200bfa976483b4eaa0b7685c6e24 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament-widgets::components.widget','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament-widgets::widget'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <?php if (isset($component)) { $__componentOriginalee08b1367eba38734199cf7829b1d1e9 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalee08b1367eba38734199cf7829b1d1e9 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => ['xData' => '{
                locating: false,
                locationError: null,
                async formatLocation(position) {
                    const coords = position.coords;
                    const latitude = Number(coords.latitude).toFixed(6);
                    const longitude = Number(coords.longitude).toFixed(6);
                    const accuracy = coords.accuracy ? Math.round(coords.accuracy) : null;
                    const coordinateText = accuracy
                        ? `Lat: ${latitude}, Lng: ${longitude} (accuracy ${accuracy}m)`
                        : `Lat: ${latitude}, Lng: ${longitude}`;
                    const googleMapsUrl = `https://www.google.com/maps?q=${latitude},${longitude}`;

                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`,
                            { headers: { Accept: \'application/json\' } },
                        );

                        if (response.ok) {
                            const data = await response.json();
                            const address = data?.display_name;

                            if (address) {
                                return `${address} | ${coordinateText} | Google Maps: ${googleMapsUrl}`;
                            }
                        }
                    } catch (error) {
                        // Fall back to coordinates when reverse geocoding is unavailable.
                    }

                    return `${coordinateText} | Google Maps: ${googleMapsUrl}`;
                },
                async captureLocation(property, method) {
                    this.locationError = null;

                    if (! navigator.geolocation) {
                        this.locationError = \'Location is required, but it is not supported on this device or browser.\';

                        return;
                    }

                    this.locating = true;
                    navigator.geolocation.getCurrentPosition(
                        async (position) => {
                            await $wire.set(property, await this.formatLocation(position));
                            this.locating = false;
                            await $wire.call(method);
                        },
                        async (error) => {
                            this.locationError = `Location is required: ${error.message}`;
                            this.locating = false;
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 0,
                        },
                    );
                },
            }']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['x-data' => '{
                locating: false,
                locationError: null,
                async formatLocation(position) {
                    const coords = position.coords;
                    const latitude = Number(coords.latitude).toFixed(6);
                    const longitude = Number(coords.longitude).toFixed(6);
                    const accuracy = coords.accuracy ? Math.round(coords.accuracy) : null;
                    const coordinateText = accuracy
                        ? `Lat: ${latitude}, Lng: ${longitude} (accuracy ${accuracy}m)`
                        : `Lat: ${latitude}, Lng: ${longitude}`;
                    const googleMapsUrl = `https://www.google.com/maps?q=${latitude},${longitude}`;

                    try {
                        const response = await fetch(
                            `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`,
                            { headers: { Accept: \'application/json\' } },
                        );

                        if (response.ok) {
                            const data = await response.json();
                            const address = data?.display_name;

                            if (address) {
                                return `${address} | ${coordinateText} | Google Maps: ${googleMapsUrl}`;
                            }
                        }
                    } catch (error) {
                        // Fall back to coordinates when reverse geocoding is unavailable.
                    }

                    return `${coordinateText} | Google Maps: ${googleMapsUrl}`;
                },
                async captureLocation(property, method) {
                    this.locationError = null;

                    if (! navigator.geolocation) {
                        this.locationError = \'Location is required, but it is not supported on this device or browser.\';

                        return;
                    }

                    this.locating = true;
                    navigator.geolocation.getCurrentPosition(
                        async (position) => {
                            await $wire.set(property, await this.formatLocation(position));
                            this.locating = false;
                            await $wire.call(method);
                        },
                        async (error) => {
                            this.locationError = `Location is required: ${error.message}`;
                            this.locating = false;
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 0,
                        },
                    );
                },
            }']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

            <?php
                $shiftStart = $data['shiftWindow']['start'] ?? null;
                $shiftEnd = $data['shiftWindow']['end'] ?? null;
            ?>
             <?php $__env->slot('heading', null, []); ?> 
                <?php
                    $headingEc = $data['assignment']?->assigned_date
                        ? \App\Support\EthiopianDate::toEcYmdAmharic($data['assignment']->assigned_date)
                        : \App\Support\EthiopianDate::toEcYmdAmharic(\Illuminate\Support\Carbon::now('Africa/Addis_Ababa'));
                ?>
                <?php echo e(__('My attendance')); ?> — <?php echo e($data['assignment'] ? $data['assignment']->shift?->name . ' · ' . ($headingEc ?? '') : ($headingEc ?? __('Today'))); ?>

             <?php $__env->endSlot(); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(! $data['assignment']): ?>
                <p class="text-gray-600 dark:text-gray-400">
                    You have no shift assigned for today. Check-in and check-out are available only on days you have a scheduled shift.
                </p>
            <?php elseif($data['checkedOut']): ?>
                <p class="text-gray-600 dark:text-gray-400">
                    <?php echo e(__('Check-out recorded at')); ?> <?php echo e($data['attendance']->check_out ? \App\Support\EthiopianDate::toEcAmharicDateAndTime($data['attendance']->check_out) : ''); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['attendance']->check_out_location): ?>
                        · <?php echo e($data['attendance']->check_out_location); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
                <a href="<?php echo e(\App\Filament\Resources\ShiftReportResource::getUrl('create') . '?' . http_build_query(['employee_id' => $data['assignment']->employee_id, 'shift_assignment_id' => $data['assignment']->id])); ?>"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border border-transparent bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2">
                    Submit shift report
                </a>
            <?php elseif($data['shiftNotStarted']): ?>
                <p class="text-amber-700 dark:text-amber-400">
                    <?php echo e(__('Check-in is disabled until your shift starts')); ?> (<?php echo e($shiftStart ? \App\Support\EthiopianDate::toEcAmharicDateAndTime($shiftStart) : ''); ?> – <?php echo e($shiftEnd ? \App\Support\EthiopianDate::toEcAmharicDateAndTime($shiftEnd) : ''); ?>).
                </p>
                <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'button','color' => 'gray','icon' => 'heroicon-o-check-circle','disabled' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','color' => 'gray','icon' => 'heroicon-o-check-circle','disabled' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    Check in
                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
            <?php elseif($data['shiftEndedWithoutCheckIn']): ?>
                <div class="rounded-lg border border-danger-200 bg-danger-50 p-4 dark:border-danger-900/50 dark:bg-danger-950/30">
                    <p class="text-sm font-semibold text-danger-800 dark:text-danger-200">
                        <?php echo e(__('Absent for today')); ?>

                    </p>
                    <p class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                        <?php echo e(__('Your shift for today has ended and you did not check in. Status: Absent. Check-in is closed for this day.')); ?>

                    </p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($shiftEnd): ?>
                        <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                            <?php echo e(__('Shift window ended')); ?>: <?php echo e(\App\Support\EthiopianDate::toEcAmharicDateAndTime($shiftEnd)); ?>

                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'button','color' => 'gray','icon' => 'heroicon-o-check-circle','class' => 'mt-3','disabled' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','color' => 'gray','icon' => 'heroicon-o-check-circle','class' => 'mt-3','disabled' => true]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                    <?php echo e(__('Check in')); ?>

                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
            <?php elseif($data['canCheckIn']): ?>
                <div class="space-y-4">
                    <template x-if="locationError">
                        <p class="text-sm text-danger-600 dark:text-danger-400" x-text="locationError"></p>
                    </template>
                    <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'button','color' => 'success','icon' => 'heroicon-o-check-circle','xBind:disabled' => 'locating','xOn:click' => 'captureLocation(\'checkInLocation\', \'requestCheckIn\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','color' => 'success','icon' => 'heroicon-o-check-circle','x-bind:disabled' => 'locating','x-on:click' => 'captureLocation(\'checkInLocation\', \'requestCheckIn\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <span x-text="locating ? 'Getting location...' : 'Check in'"></span>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
                </div>
            <?php elseif($data['canCheckOut']): ?>
                <div class="space-y-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <?php echo e(__('Checked in at')); ?> <?php echo e($data['attendance']->check_in ? \App\Support\EthiopianDate::toEcAmharicDateAndTime($data['attendance']->check_in) : ''); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data['attendance']->check_in_location): ?>
                            · <?php echo e($data['attendance']->check_in_location); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                    <template x-if="locationError">
                        <p class="text-sm text-danger-600 dark:text-danger-400" x-text="locationError"></p>
                    </template>

                    <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'button','color' => 'primary','icon' => 'heroicon-o-arrow-right-on-rectangle','xBind:disabled' => 'locating','xOn:click' => 'captureLocation(\'checkOutLocation\', \'requestCheckOut\')']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','color' => 'primary','icon' => 'heroicon-o-arrow-right-on-rectangle','x-bind:disabled' => 'locating','x-on:click' => 'captureLocation(\'checkOutLocation\', \'requestCheckOut\')']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                        <span x-text="locating ? 'Getting location...' : 'Check out & go to report'"></span>
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
                </div>
            <?php elseif($data['shiftEnded']): ?>
                <p class="text-gray-600 dark:text-gray-400">
                    <?php echo e(__('Your shift window has ended. Check-in and check-out are no longer available for this shift.')); ?>

                </p>
            <?php elseif(! $data['withinShift']): ?>
                <p class="text-amber-700 dark:text-amber-400">
                    You can check in and check out only during your active shift window.
                </p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $attributes = $__attributesOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__attributesOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalee08b1367eba38734199cf7829b1d1e9)): ?>
<?php $component = $__componentOriginalee08b1367eba38734199cf7829b1d1e9; ?>
<?php unset($__componentOriginalee08b1367eba38734199cf7829b1d1e9); ?>
<?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showLateCheckInModal): ?>
            <div
                class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-950/50 p-4 dark:bg-gray-950/80"
                wire:click="cancelLateCheckInModal"
                <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'late-checkin-modal'; ?>wire:key="late-checkin-modal"
            >
                <div
                    class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
                    wire:click.stop
                >
                    <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                        <?php echo e(__('You are checking in late')); ?>

                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        <?php echo e(__('Please explain why you are late. This will be saved on your attendance record.')); ?>

                    </p>
                    <div class="mt-4">
                        <label class="sr-only" for="late-reason-input"><?php echo e(__('Reason for late check-in')); ?></label>
                        <textarea
                            id="late-reason-input"
                            wire:model.live="lateReason"
                            rows="4"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-white/10 dark:bg-white/5"
                            placeholder="<?php echo e(__('Enter your reason…')); ?>"
                        ></textarea>
                    </div>
                    <div class="mt-6 flex flex-wrap justify-end gap-2">
                        <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'button','color' => 'gray','wire:click' => 'cancelLateCheckInModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','color' => 'gray','wire:click' => 'cancelLateCheckInModal']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Cancel')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'button','color' => 'success','wire:click' => 'confirmLateCheckIn','icon' => 'heroicon-o-check-circle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','color' => 'success','wire:click' => 'confirmLateCheckIn','icon' => 'heroicon-o-check-circle']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Confirm check-in')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCheckoutReasonModal): ?>
            <div
                class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-950/50 p-4 dark:bg-gray-950/80"
                wire:click="cancelCheckoutReasonModal"
                <?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::$currentLoop['key'] = 'checkout-reason-modal'; ?>wire:key="checkout-reason-modal"
            >
                <div
                    class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
                    wire:click.stop
                >
                    <h3 class="text-lg font-semibold text-gray-950 dark:text-white">
                        <?php echo e(__('Additional information required')); ?>

                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        <?php echo e(__('Please provide the reason(s) below. They will be saved on your attendance record.')); ?>

                    </p>

                    <div class="mt-4 space-y-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($checkoutModalNeedsEarly): ?>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300" for="early-checkout-reason-input">
                                    <?php echo e(__('Reason for early checkout')); ?>

                                </label>
                                <textarea
                                    id="early-checkout-reason-input"
                                    wire:model.live="earlyCheckoutReason"
                                    rows="3"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-white/10 dark:bg-white/5"
                                    placeholder="<?php echo e(__('Enter your reason…')); ?>"
                                ></textarea>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($checkoutModalNeedsHalfDay): ?>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300" for="half-day-reason-input">
                                    <?php echo e(__('Reason for half day')); ?>

                                </label>
                                <textarea
                                    id="half-day-reason-input"
                                    wire:model.live="halfDayReason"
                                    rows="3"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-white/10 dark:bg-white/5"
                                    placeholder="<?php echo e(__('Enter your reason…')); ?>"
                                ></textarea>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="mt-6 flex flex-wrap justify-end gap-2">
                        <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'button','color' => 'gray','wire:click' => 'cancelCheckoutReasonModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','color' => 'gray','wire:click' => 'cancelCheckoutReasonModal']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Cancel')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
                        <?php if (isset($component)) { $__componentOriginal6330f08526bbb3ce2a0da37da512a11f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.button.index','data' => ['type' => 'button','color' => 'primary','wire:click' => 'confirmCheckoutReasons','icon' => 'heroicon-o-arrow-right-on-rectangle']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'button','color' => 'primary','wire:click' => 'confirmCheckoutReasons','icon' => 'heroicon-o-arrow-right-on-rectangle']); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

                            <?php echo e(__('Confirm check-out')); ?>

                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $attributes = $__attributesOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__attributesOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f)): ?>
<?php $component = $__componentOriginal6330f08526bbb3ce2a0da37da512a11f; ?>
<?php unset($__componentOriginal6330f08526bbb3ce2a0da37da512a11f); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $attributes = $__attributesOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__attributesOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24)): ?>
<?php $component = $__componentOriginalb525200bfa976483b4eaa0b7685c6e24; ?>
<?php unset($__componentOriginalb525200bfa976483b4eaa0b7685c6e24); ?>
<?php endif; ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\wamp64\www\Denb-main\hr-callcenter-system\resources\views/filament/resources/attendance-resource/widgets/officer-attendance-widget.blade.php ENDPATH**/ ?>