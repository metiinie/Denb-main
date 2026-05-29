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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'filament::components.section.index','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('filament::section'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::processComponentKey($component); ?>

        <div class="p-4">
            <h2 class="text-lg font-bold mb-4">Quick Actions</h2>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 border border-gray-100 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-primary-700"><?php echo e($this->getPendingCount()); ?></div>
                    <div class="text-sm text-gray-600">Pending Complaints</div>
                    <a href="<?php echo e(\App\Filament\Resources\ComplaintResource::getUrl('index', ['tableFilters[status][value]' => 'pending'])); ?>"
                        class="text-xs text-primary-600 mt-2 inline-block font-medium hover:underline">
                        View All ->
                    </a>
                </div>

                <div class="bg-red-50 border border-red-100 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-danger-700"><?php echo e($this->getUrgentCount()); ?></div>
                    <div class="text-sm text-gray-600">Urgent Cases</div>
                    <a href="<?php echo e(\App\Filament\Resources\ComplaintResource::getUrl('index', ['tableFilters[priority][value]' => 'high'])); ?>"
                        class="text-xs text-danger-600 mt-2 inline-block font-medium hover:underline">
                        View All ->
                    </a>
                </div>

                <div class="bg-amber-50 border border-amber-100 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-warning-700"><?php echo e($this->getSupervisorQueueCount()); ?></div>
                    <div class="text-sm text-gray-600">Pending Supervisor Review</div>
                    <a href="<?php echo e(\App\Filament\Resources\TipResource::getUrl('index', ['tableFilters[status][value]' => \App\Models\Tip::STATUS_PENDING_SUPERVISOR_REVIEW])); ?>"
                        class="text-xs text-warning-600 mt-2 inline-block font-medium hover:underline">
                        View All ->
                    </a>
                </div>

                <div class="bg-red-50 border border-red-100 p-4 rounded-lg">
                    <div class="text-2xl font-bold text-danger-700"><?php echo e($this->getDirectorQueueCount()); ?></div>
                    <div class="text-sm text-gray-600">Pending Director Review</div>
                    <a href="<?php echo e(\App\Filament\Resources\TipResource::getUrl('index', ['tableFilters[status][value]' => \App\Models\Tip::STATUS_PENDING_DIRECTOR_REVIEW])); ?>"
                        class="text-xs text-danger-600 mt-2 inline-block font-medium hover:underline">
                        View All ->
                    </a>
                </div>
            </div>

            <div class="mt-4 flex gap-2">
                <a href="<?php echo e(\App\Filament\Resources\ComplaintResource::getUrl('index')); ?>"
                    class="flex-1 bg-gray-100 text-center py-2 rounded text-sm font-medium hover:bg-gray-200 transition-colors">
                    All Complaints
                </a>
                <a href="<?php echo e(\App\Filament\Resources\TipResource::getUrl('index')); ?>"
                    class="flex-1 bg-gray-100 text-center py-2 rounded text-sm font-medium hover:bg-gray-200 transition-colors">
                    All Tips
                </a>
            </div>
        </div>
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
<?php /**PATH C:\wamp64\www\Denb-main\hr-callcenter-system\resources\views/filament/widgets/case-management.blade.php ENDPATH**/ ?>