#!/bin/bash

# Check if gcloud is installed
echo "Checking if gcloud is installed..."

if ! command -v gcloud &> /dev/null
then
    echo "gcloud command not found, installing Google Cloud SDK..."

    # Step 2: Install Google Cloud SDK (gcloud CLI)
    # Check the OS type and install accordingly

    if [[ "$OSTYPE" == "linux-gnu"* ]]; then
        # For Linux
        echo "Installing Google Cloud SDK on Linux..."
        curl https://sdk.cloud.google.com | bash
    elif [[ "$OSTYPE" == "darwin"* ]]; then
        # For macOS
        echo "Installing Google Cloud SDK on macOS..."
        curl https://sdk.cloud.google.com | bash
    elif [[ "$OSTYPE" == "msys" ]]; then
        # For Windows (if using Git Bash or similar)
        echo "For Windows, please manually install Google Cloud SDK from https://cloud.google.com/sdk/docs/install"
        exit 1
    else
        echo "Unsupported OS. Please manually install Google Cloud SDK from https://cloud.google.com/sdk/docs/install"
        exit 1
    fi

    # Restart shell to initialize gcloud
    exec -l $SHELL
else
    echo "gcloud is already installed."
fi

#Init gcloud
gcloud auth login

#Config Project
gcloud config set project intro-to-databases-443419

# Open SSH Tunnel to the VM
echo "Opening SSH tunnel to VM..."
gcloud compute ssh instance-vm --zone=us-central1-a -- -L 3306:127.0.0.1:3306 -N -t &

# Redirect to login page in Safari
echo "Opening the login page in Safari..."
open -a "Safari" http://localhost/fantasyProject/login.html
