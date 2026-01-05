<script setup lang="ts">
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/AppLayout.vue';
import { show as showJob } from '@/routes/jobs';
import { bulk } from '@/routes/jobs/photos';
import { destroy, togglePublic } from '@/routes/photos';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import {
    AlertCircle,
    ArrowLeft,
    CheckCircle,
    Eye,
    EyeOff,
    Image as ImageIcon,
    Trash2,
    Upload,
    XCircle,
} from 'lucide-vue-next';
import { computed, ref } from 'vue';

interface Customer {
    id: number;
    name: string;
}

interface Asset {
    id: number;
    asset_tag: string;
    asset_name: string;
}

interface WorkshopJob {
    id: number;
    job_reference: string;
    customer?: Customer;
    asset?: Asset;
}

interface User {
    id: number;
    name: string;
}

interface Photo {
    id: number;
    file_path: string;
    photo_stage: string;
    description?: string;
    location_context?: string;
    is_public: boolean;
    created_at: string;
    user: User;
}

interface PhotoGallery {
    [key: string]: {
        photos: Photo[];
        minimum_required: number;
        has_minimum: boolean;
    };
}

interface PhotoStage {
    value: string;
    label: string;
}

interface Props {
    job: WorkshopJob;
    photoGallery: PhotoGallery;
    photoStages: PhotoStage[];
    canUpload: boolean;
}

const props = defineProps<Props>();

// Upload dialog state
const showUploadDialog = ref(false);
const selectedPhoto = ref<Photo | null>(null);
const showPhotoDialog = ref(false);

// Upload form
const uploadForm = useForm({
    photos: [] as File[],
    photo_stage: '',
    description: '',
    location_context: '',
    is_public: true,
});

// Handle file selection
const handleFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files) {
        uploadForm.photos = Array.from(target.files);
    }
};

// Submit upload
const submitUpload = () => {
    const formData = new FormData();
    uploadForm.photos.forEach((file) => {
        formData.append('photos[]', file);
    });
    formData.append('photo_stage', uploadForm.photo_stage);
    formData.append('description', uploadForm.description);
    formData.append('location_context', uploadForm.location_context);
    formData.append('is_public', uploadForm.is_public ? '1' : '0');

    router.post(bulk.url(props.job.id), formData, {
        onSuccess: () => {
            showUploadDialog.value = false;
            uploadForm.reset();
        },
    });
};

// Delete photo
const deletePhoto = (photoId: number) => {
    if (confirm('Are you sure you want to delete this photo?')) {
        router.delete(destroy.url(photoId));
    }
};

// Toggle photo visibility
const toggleVisibility = (photoId: number) => {
    router.post(togglePublic.url(photoId));
};

// View photo
const viewPhoto = (photo: Photo) => {
    selectedPhoto.value = photo;
    showPhotoDialog.value = true;
};

// Get stage badge variant
const getStageBadgeVariant = (stage: string): 'default' | 'secondary' => {
    const criticalStages = [
        'initial_assessment',
        'repair_in_progress',
        'final_inspection',
    ];
    return criticalStages.includes(stage) ? 'default' : 'secondary';
};

// Format date
const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleString('en-MY', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

// Get photo URL
const getPhotoUrl = (path: string) => {
    return `/storage/${path}`;
};

// Total photos count
const totalPhotos = computed(() => {
    return Object.values(props.photoGallery).reduce(
        (sum, stage) => sum + stage.photos.length,
        0,
    );
});

// Missing photos count
const missingPhotosCount = computed(() => {
    return Object.values(props.photoGallery).filter(
        (stage) => !stage.has_minimum,
    ).length;
});
</script>

<template>
    <AppLayout>
        <Head :title="`Photos - ${job.job_reference}`" />

        <div class="max-w-7xl space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" size="icon" as-child>
                        <Link :href="showJob.url(job.id)">
                            <ArrowLeft class="h-4 w-4" />
                        </Link>
                    </Button>
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight">
                            Photo Gallery
                        </h1>
                        <p class="text-muted-foreground">
                            {{ job.job_reference }} - {{ totalPhotos }} photos
                        </p>
                    </div>
                </div>
                <Button v-if="canUpload" @click="showUploadDialog = true">
                    <Upload class="mr-2 h-4 w-4" />
                    Upload Photos
                </Button>
            </div>

            <!-- Job Info -->
            <Card>
                <CardHeader>
                    <CardTitle>Job Information</CardTitle>
                </CardHeader>
                <CardContent class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div v-if="job.asset">
                        <Label class="text-muted-foreground">Asset</Label>
                        <p class="font-medium">{{ job.asset.asset_name }}</p>
                        <p class="text-sm text-muted-foreground">
                            {{ job.asset.asset_tag }}
                        </p>
                    </div>
                    <div v-if="job.customer">
                        <Label class="text-muted-foreground">Customer</Label>
                        <p class="font-medium">{{ job.customer.name }}</p>
                    </div>
                </CardContent>
            </Card>

            <!-- Photo Requirements Alert -->
            <Alert
                v-if="missingPhotosCount > 0"
                variant="default"
                class="border-yellow-500 bg-yellow-50"
            >
                <AlertCircle class="h-4 w-4 text-yellow-600" />
                <AlertDescription>
                    <strong>{{ missingPhotosCount }} stage(s)</strong> do not
                    have the minimum required photos. Upload at least 3 photos
                    for each critical stage.
                </AlertDescription>
            </Alert>

            <Alert v-else class="border-green-500 bg-green-50">
                <CheckCircle class="h-4 w-4 text-green-600" />
                <AlertDescription>
                    All stages have the minimum required photos.
                </AlertDescription>
            </Alert>

            <!-- Photo Gallery by Stage -->
            <div class="space-y-6">
                <div
                    v-for="(stageData, stageName) in photoGallery"
                    :key="stageName"
                >
                    <Card>
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <div>
                                    <CardTitle class="flex items-center gap-2">
                                        {{
                                            photoStages.find(
                                                (s) => s.value === stageName,
                                            )?.label || stageName
                                        }}
                                        <Badge
                                            :variant="
                                                getStageBadgeVariant(
                                                    stageName as string,
                                                )
                                            "
                                        >
                                            {{ stageData.photos.length }} photos
                                        </Badge>
                                    </CardTitle>
                                    <CardDescription>
                                        Minimum required:
                                        {{ stageData.minimum_required }}
                                        <CheckCircle
                                            v-if="stageData.has_minimum"
                                            class="ml-2 inline h-4 w-4 text-green-600"
                                        />
                                        <XCircle
                                            v-else
                                            class="ml-2 inline h-4 w-4 text-red-600"
                                        />
                                    </CardDescription>
                                </div>
                                <Button
                                    v-if="canUpload"
                                    variant="outline"
                                    size="sm"
                                    @click="
                                        () => {
                                            uploadForm.photo_stage =
                                                stageName as string;
                                            showUploadDialog = true;
                                        }
                                    "
                                >
                                    <Upload class="mr-2 h-4 w-4" />
                                    Upload
                                </Button>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div
                                v-if="stageData.photos.length === 0"
                                class="py-8 text-center text-muted-foreground"
                            >
                                <ImageIcon
                                    class="mx-auto mb-2 h-12 w-12 opacity-50"
                                />
                                <p>No photos uploaded for this stage yet</p>
                            </div>

                            <div
                                v-else
                                class="grid grid-cols-2 gap-4 md:grid-cols-4 lg:grid-cols-6"
                            >
                                <div
                                    v-for="photo in stageData.photos"
                                    :key="photo.id"
                                    class="group relative"
                                >
                                    <div
                                        class="aspect-square cursor-pointer overflow-hidden rounded-lg border-2 border-transparent bg-muted transition-colors hover:border-primary"
                                        @click="viewPhoto(photo)"
                                    >
                                        <img
                                            :src="getPhotoUrl(photo.file_path)"
                                            :alt="
                                                photo.description || 'Job photo'
                                            "
                                            class="h-full w-full object-cover"
                                        />
                                    </div>

                                    <!-- Photo Actions Overlay -->
                                    <div
                                        class="absolute inset-0 flex items-center justify-center gap-2 rounded-lg bg-black/60 opacity-0 transition-opacity group-hover:opacity-100"
                                    >
                                        <Button
                                            size="icon"
                                            variant="secondary"
                                            @click.stop="
                                                toggleVisibility(photo.id)
                                            "
                                            :title="
                                                photo.is_public
                                                    ? 'Make Private'
                                                    : 'Make Public'
                                            "
                                        >
                                            <Eye
                                                v-if="photo.is_public"
                                                class="h-4 w-4"
                                            />
                                            <EyeOff v-else class="h-4 w-4" />
                                        </Button>
                                        <Button
                                            size="icon"
                                            variant="destructive"
                                            @click.stop="deletePhoto(photo.id)"
                                            title="Delete Photo"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>

                                    <!-- Visibility Badge -->
                                    <div class="absolute top-2 right-2">
                                        <Badge
                                            variant="secondary"
                                            class="text-xs"
                                        >
                                            <Eye
                                                v-if="photo.is_public"
                                                class="mr-1 h-3 w-3"
                                            />
                                            <EyeOff
                                                v-else
                                                class="mr-1 h-3 w-3"
                                            />
                                        </Badge>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>

        <!-- Upload Photos Dialog -->
        <Dialog v-model:open="showUploadDialog">
            <DialogContent class="max-w-2xl">
                <DialogHeader>
                    <DialogTitle>Upload Photos</DialogTitle>
                    <DialogDescription>
                        Upload photos for this job. You can select multiple
                        files at once.
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-4 py-4">
                    <!-- Photo Stage -->
                    <div class="space-y-2">
                        <Label for="photo_stage">
                            Photo Stage <span class="text-destructive">*</span>
                        </Label>
                        <Select v-model="uploadForm.photo_stage">
                            <SelectTrigger id="photo_stage">
                                <SelectValue placeholder="Select stage" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="stage in photoStages"
                                    :key="stage.value"
                                    :value="stage.value"
                                >
                                    {{ stage.label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <!-- File Upload -->
                    <div class="space-y-2">
                        <Label for="photos">
                            Photos <span class="text-destructive">*</span>
                        </Label>
                        <Input
                            id="photos"
                            type="file"
                            accept="image/jpeg,image/jpg,image/png"
                            multiple
                            @change="handleFileSelect"
                        />
                        <p class="text-sm text-muted-foreground">
                            Accepted: JPG, PNG | Max 10MB per file | Multiple
                            files allowed
                        </p>
                        <p v-if="uploadForm.photos.length > 0" class="text-sm">
                            {{ uploadForm.photos.length }} file(s) selected
                        </p>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <Label for="description">Description</Label>
                        <Textarea
                            id="description"
                            v-model="uploadForm.description"
                            placeholder="Describe what these photos show..."
                            rows="2"
                        />
                    </div>

                    <!-- Location Context -->
                    <div class="space-y-2">
                        <Label for="location_context">Location/Context</Label>
                        <Input
                            id="location_context"
                            v-model="uploadForm.location_context"
                            type="text"
                            placeholder="e.g., Front panel, Engine bay, Left side"
                        />
                    </div>

                    <!-- Public Visibility -->
                    <div class="flex items-center space-x-2">
                        <input
                            id="is_public"
                            v-model="uploadForm.is_public"
                            type="checkbox"
                            class="rounded"
                        />
                        <Label for="is_public" class="cursor-pointer">
                            Make photos publicly visible
                        </Label>
                    </div>
                </div>

                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="showUploadDialog = false"
                        :disabled="uploadForm.processing"
                    >
                        Cancel
                    </Button>
                    <Button
                        @click="submitUpload"
                        :disabled="
                            uploadForm.processing ||
                            uploadForm.photos.length === 0 ||
                            !uploadForm.photo_stage
                        "
                    >
                        {{
                            uploadForm.processing
                                ? 'Uploading...'
                                : 'Upload Photos'
                        }}
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- View Photo Dialog -->
        <Dialog v-model:open="showPhotoDialog">
            <DialogContent class="max-w-4xl">
                <DialogHeader>
                    <DialogTitle>Photo Details</DialogTitle>
                </DialogHeader>

                <div v-if="selectedPhoto" class="space-y-4">
                    <div class="overflow-hidden rounded-lg bg-muted">
                        <img
                            :src="getPhotoUrl(selectedPhoto.file_path)"
                            :alt="selectedPhoto.description || 'Job photo'"
                            class="h-auto w-full"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <Label class="text-muted-foreground">Stage</Label>
                            <p>
                                {{
                                    photoStages.find(
                                        (s) =>
                                            s.value ===
                                            selectedPhoto.photo_stage,
                                    )?.label
                                }}
                            </p>
                        </div>
                        <div>
                            <Label class="text-muted-foreground"
                                >Visibility</Label
                            >
                            <Badge>
                                <Eye
                                    v-if="selectedPhoto.is_public"
                                    class="mr-1 h-3 w-3"
                                />
                                <EyeOff v-else class="mr-1 h-3 w-3" />
                                {{
                                    selectedPhoto.is_public
                                        ? 'Public'
                                        : 'Private'
                                }}
                            </Badge>
                        </div>
                        <div v-if="selectedPhoto.description">
                            <Label class="text-muted-foreground"
                                >Description</Label
                            >
                            <p>{{ selectedPhoto.description }}</p>
                        </div>
                        <div v-if="selectedPhoto.location_context">
                            <Label class="text-muted-foreground"
                                >Location</Label
                            >
                            <p>{{ selectedPhoto.location_context }}</p>
                        </div>
                        <div>
                            <Label class="text-muted-foreground"
                                >Uploaded By</Label
                            >
                            <p>{{ selectedPhoto.user.name }}</p>
                        </div>
                        <div>
                            <Label class="text-muted-foreground"
                                >Uploaded At</Label
                            >
                            <p>
                                {{ formatDateTime(selectedPhoto.created_at) }}
                            </p>
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <Button @click="showPhotoDialog = false"> Close </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
