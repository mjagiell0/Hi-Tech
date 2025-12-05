<?php

enum OrderStatusEnum: string
{
    case PENDING = "Pending";
    case PROCESSING = "Processing";
    case COMPLETED = "Completed";
    case CANCELLED = "Cancelled";
}
