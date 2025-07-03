import { PartialType } from '@nestjs/swagger';
import { CreateApostillaDto } from './create-apostilla.dto';

export class UpdateApostillaDto extends PartialType(CreateApostillaDto) {}
