#!/bin/bash


function RenameFilesToOrigFiles {
    dir_name=$1
    cd $dir_name
    for single_file in `ls`
    do
        if [[ $single_file == *"_orig"* ]]
        then
            continue
        fi

        file_name=`echo $single_file | cut -d \. -f 1`
        extension=`echo $single_file | cut -d \. -f 2`
        new_file_name=$file_name
        new_file_name+="_orig."
        new_file_name+=$extension

        mv $single_file $new_file_name

    done
    cd -
}

function ConvertImageSize {
    dir_name=$1
    image_size=$2
    dest_dir=$3
    cd $dir_name
    for single_file in `ls`
    do
        if [[ $single_file != *"_orig"* ]]
        then
            continue
        fi

        item_code=`echo $single_file | cut -d \_ -f 2`
        extension=`echo $single_file | cut -d \. -f 2`
        new_file_name="../"
        new_file_name+=$dest_dir
        new_file_name+="/product_"
        new_file_name+=$item_code
        new_file_name+="."
        new_file_name+=$extension

        if [ -f "$new_file_name" ]
        then
            continue
        fi

        convert $single_file  -resize $image_sizex$image_size $new_file_name

    done
    cd -
}

s3_bucket="compare.prices.frankfurt/product_images"
function UploadImagesToS3 {
    images_dir=$1
    current_dir=`pwd`
    cd $images_dir
    for single_file in `ls`
    do
        /usr/local/bin/s3cmd -P --no-preserve --guess-mime-type --config $current_dir/s3cfg put $single_file s3://$s3_bucket/
        break
    done
    cd -
    
}

#RenameFilesToOrigFiles "../../Images/Images_png_orig/"
#RenameFilesToOrigFiles "../../Images/Images_jpg_orig/"

#ConvertImageSize "../../Images/Images_png_orig/" "120" "Images_png"
#ConvertImageSize "../../Images/Images_jpg_orig/" "120" "Images_jpg"

#file_name="tests/product_40084107_orig.png"
#/usr/local/bin/s3cmd -P --no-preserve --guess-mime-type --config s3cfg put $file_name s3://$s3_bucket/  

#UploadImagesToS3 "../../Images/Images_png/"

#/usr/local/bin/s3cmd -P --no-preserve --guess-mime-type --config s3cfg sync "../../Images/Images_png/" s3://$s3_bucket/  
#/usr/local/bin/s3cmd -P --no-preserve --guess-mime-type --config s3cfg sync "../../Images/Images_jpg/" s3://$s3_bucket/  

/usr/local/bin/s3cmd setacl s3://$s3_bucket/product_7296021430459.jpg --acl-public --config s3cfg
