from django.db import models


class User(models.Model):
    uid = models.CharField(max_length=20)
    plate_no = models.CharField(
        max_length=10, default=None, blank=True, null=True)
    plate_file = models.CharField(
        max_length=10, default=None, blank=True, null=True)
    status = models.IntegerField(default=0)
    log_in = models.DateTimeField(auto_now_add=True)
    log_out = models.DateTimeField(default=None, blank=True, null=True)

    def __str__(self):
        return self.uid
